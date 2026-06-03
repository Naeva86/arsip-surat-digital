<?php
// app/Http/Controllers/DisposisiController.php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\DisposisiLog;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Models\Bagian;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class DisposisiController extends Controller
{
    /**
     * Kotak Disposisi — satu halaman untuk Direktur, Kabag, Kasubag
     * Data di-isolasi per role/user
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['direktur', 'kabag', 'kasubbag', 'admin'])) {
            abort(403);
        }

        $query = Disposisi::with(['suratMasuk.kategori', 'suratMasuk.user', 'dariUser', 'kepadaUser'])
            ->where('kepada_user_id', $user->id)
            ->whereHas('suratMasuk');

        // Status filter
        if ($user->role === 'kasubbag') {
            if ($request->filled('status_filter')) {
                $query->where('status', $request->status_filter);
            } else {
                $query->whereIn('status', ['menunggu', 'dibaca', 'selesai']);
            }
        } else {
            $query->whereIn('status', ['menunggu', 'dibaca']);
        }

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('suratMasuk', function ($q) use ($s) {
                $q->where('judul_surat', 'like', "%$s%")
                ->orWhere('pengirim', 'like', "%$s%")
                ->orWhere('nomor_surat', 'like', "%$s%")
                ->orWhere('no_agenda', 'like', "%$s%");
            });
        }

        $disposisis = $query->latest()->paginate(10)->withQueryString();

        return view('disposisi.index', compact('disposisis'));
    }

    /**
     * AJAX — Kasubag tandai selesai saat buka detail
     */
    public function tandaiSelesai(Disposisi $disposisi)
    {
        $user = auth()->user();

        if ($disposisi->kepada_user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (in_array($disposisi->status, ['menunggu', 'dibaca'])) {
            $disposisi->update([
                'status'     => 'selesai',
                'dibaca_at'  => $disposisi->dibaca_at ?? now(),
                'selesai_at' => now(),
            ]);

            $disposisi->suratMasuk->update(['status' => 'selesai']);

            DisposisiLog::create([
                'disposisi_id' => $disposisi->id,
                'user_id'      => $user->id,
                'aksi'         => 'Disposisi diterima dan diselesaikan oleh Kasubag',
                'keterangan'   => $user->name . ' telah menerima disposisi',
            ]);
        }

        return response()->json(['success' => true, 'status' => 'selesai']);
    }

    public function show(Disposisi $disposisi)
    {
        $user = auth()->user();

        if ($disposisi->kepada_user_id !== $user->id && $user->role !== 'admin') {
            abort(403);
        }

        $disposisi->load(['suratMasuk.kategori', 'suratMasuk.user', 'suratMasuk.disposisis.dariUser', 'suratMasuk.disposisis.kepadaUser', 'dariUser']);

        // Tandai dibaca (direktur/kabag saja)
        if ($disposisi->status === 'menunggu' && $user->role !== 'kasubbag') {
            $disposisi->update(['status' => 'dibaca', 'dibaca_at' => now()]);
            DisposisiLog::create([
                'disposisi_id' => $disposisi->id,
                'user_id'      => $user->id,
                'aksi'         => 'Disposisi dibaca',
                'keterangan'   => $user->name . ' membaca disposisi',
            ]);
        }

        $bagians  = collect();
        $kasubags = collect();

        if ($user->role === 'direktur') {
            $bagians = Bagian::whereNull('parent_id')->orderBy('nama_bagian')->get();
        } elseif ($user->role === 'kabag') {
            $subBagians = Bagian::where('parent_id', $user->bagian_id)->orderBy('nama_bagian')->get();
            $kasubags = User::where('role', 'kasubbag')
                ->whereIn('bagian_id', $subBagians->pluck('id'))
                ->get();
        }

        return view('disposisi.show', compact('disposisi', 'bagians', 'kasubags'));
    }
    /**
     * Proses disposisi — Direktur/Kabag kirim keputusan
     */
    public function proses(Request $request, Disposisi $disposisi)
    {
        $user = auth()->user();

        if ($disposisi->kepada_user_id !== $user->id) abort(403);

        $surat = $disposisi->suratMasuk;

        // ── DIREKTUR ──
        if ($user->role === 'direktur') {

            $request->validate([
                'keputusan' => 'required|in:setuju,ditolak',
                'catatan'   => 'nullable|string|max:1000',
            ]);

            if ($request->keputusan === 'ditolak') {
                // Tolak: kembali ke staff
                $disposisi->update([
                    'status'             => 'selesai',
                    'keputusan'          => 'ditolak',
                    'catatan_penolakan'  => $request->catatan,
                    'selesai_at'         => now(),
                ]);

                $surat->update(['status' => 'ditolak']);

                DisposisiLog::create([
                    'disposisi_id' => $disposisi->id,
                    'user_id'      => $user->id,
                    'aksi'         => 'Surat ditolak oleh Direktur',
                    'keterangan'   => $request->catatan ?: 'Tanpa catatan',
                ]);

                return redirect()->route('disposisi.index')
                                 ->with('success', 'Surat berhasil ditolak dan dikembalikan ke Staff.');
            }

            // Setuju: disposisi ke Kabag
            $request->validate([
                'instruksi'       => 'required|string|max:255',
                'tujuan_bagian_id' => 'required|exists:bagians,id',
            ]);

            // Cari user Kabag di bagian tersebut
            $kabag = User::where('role', 'kabag')
                         ->where('bagian_id', $request->tujuan_bagian_id)
                         ->first();

            if (!$kabag) {
                return redirect()->back()->with('error', 'Tidak ditemukan Kabag di bagian tersebut.');
            }

            // Selesaikan disposisi Direktur
            $disposisi->update([
                'status'              => 'selesai',
                'keputusan'           => 'setuju',
                'instruksi_disposisi' => $request->instruksi,
                'tujuan_bagian_id'    => $request->tujuan_bagian_id,
                'catatan_penolakan'   => $request->catatan,
                'selesai_at'          => now(),
            ]);

            // Buat disposisi baru ke Kabag
            $newDisposisi = Disposisi::create([
                'surat_masuk_id'   => $surat->id,
                'dari_user_id'     => $user->id,
                'kepada_user_id'   => $kabag->id,
                'tujuan_bagian_id' => $request->tujuan_bagian_id,
                'level'            => 2,
                'isi_disposisi'    => $request->instruksi,
                'status'           => 'menunggu',
            ]);

            $surat->update(['status' => 'proses_disposisi']);

            DisposisiLog::create([
                'disposisi_id' => $newDisposisi->id,
                'user_id'      => $user->id,
                'aksi'         => 'Disposisi dikirim ke ' . $kabag->name,
                'keterangan'   => 'Instruksi: ' . $request->instruksi,
            ]);

            return redirect()->route('disposisi.index')
                             ->with('success', 'Disposisi berhasil dikirim ke ' . $kabag->name);
        }

        // ── KABAG ──
        if ($user->role === 'kabag') {

            $request->validate([
                'aksi_kabag' => 'required|in:selesai,lanjutkan',
                'catatan'    => 'nullable|string|max:1000',
            ]);

            if ($request->aksi_kabag === 'selesai') {
                // Disposisi selesai
                $disposisi->update([
                    'status'              => 'selesai',
                    'keputusan'           => 'setuju',
                    'instruksi_disposisi' => $request->catatan ?: 'Disposisi selesai di level Kabag',
                    'selesai_at'          => now(),
                ]);

                $surat->update(['status' => 'selesai']);

                DisposisiLog::create([
                    'disposisi_id' => $disposisi->id,
                    'user_id'      => $user->id,
                    'aksi'         => 'Disposisi diselesaikan oleh ' . $user->name,
                    'keterangan'   => $request->catatan ?: 'Selesai',
                ]);

                return redirect()->route('disposisi.index')
                                 ->with('success', 'Disposisi selesai.');
            }

            // Lanjutkan ke Kasubag
            $request->validate([
                'kepada_kasubag_id' => 'required|exists:users,id',
                'instruksi'         => 'nullable|string|max:255',
            ]);

            $kasubag = User::findOrFail($request->kepada_kasubag_id);

            $disposisi->update([
                'status'              => 'selesai',
                'keputusan'           => 'setuju',
                'instruksi_disposisi' => $request->instruksi ?: 'Diteruskan ke Kasubag',
                'selesai_at'          => now(),
            ]);

            $newDisposisi = Disposisi::create([
                'surat_masuk_id'   => $surat->id,
                'dari_user_id'     => $user->id,
                'kepada_user_id'   => $kasubag->id,
                'tujuan_bagian_id' => $kasubag->bagian_id,
                'level'            => 3,
                'isi_disposisi'    => $request->instruksi ?: 'Diteruskan ke Kasubag',
                'status'           => 'menunggu',
            ]);

            // Tandai surat selesai karena disposisi sudah sampai level terakhir
            $surat->update(['status' => 'selesai']);

            DisposisiLog::create([
                'disposisi_id' => $newDisposisi->id,
                'user_id'      => $user->id,
                'aksi'         => 'Disposisi diteruskan ke ' . $kasubag->name,
                'keterangan'   => $request->instruksi ?: 'Diteruskan',
            ]);

            return redirect()->route('disposisi.index')
                             ->with('success', 'Disposisi berhasil diteruskan ke ' . $kasubag->name);
        }

        abort(403);
    }

    /**
     * Cetak lembar disposisi
     */
    public function cetak(Disposisi $disposisi)
    {
        $disposisi->load([
            'suratMasuk.kategori',
            'suratMasuk.user',
            'suratMasuk.disposisis.dariUser.jabatan',
            'suratMasuk.disposisis.kepadaUser.jabatan',
            'suratMasuk.disposisis.kepadaUser.bagian',
            'suratMasuk.disposisis.tujuanBagian',
            'dariUser.jabatan',
            'kepadaUser.jabatan',
        ]);

        // Generate QR Code dengan logo
        $qrData = $this->generateQrCode($disposisi);

        $pdf = Pdf::loadView('pdf.lembar-disposisi', compact('disposisi', 'qrData'))
                ->setPaper('a4', 'portrait');

        $filename = 'Disposisi-' . str_replace('/', '-', $disposisi->suratMasuk->no_agenda) . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Generate QR Code base64 dengan logo PERUMDA di tengah
     */
    private function generateQrCode(Disposisi $disposisi): array
    {
        $surat = $disposisi->suratMasuk;

        // Data yang di-encode dalam QR
        $verifikasiUrl = url('/verifikasi/disposisi/' . $disposisi->id . '?hash=' . md5($disposisi->id . $surat->no_agenda . $disposisi->created_at));

        $qrText = "VERIFIKASI DISPOSISI DIGITAL\n"
                . "No Agenda: {$surat->no_agenda}\n"
                . "Nomor Surat: {$surat->nomor_surat}\n"
                . "Perihal: {$surat->judul_surat}\n"
                . "Tanggal: {$disposisi->created_at->format('d/m/Y H:i')}\n"
                . "URL: {$verifikasiUrl}";

        // QR untuk Yang Mendisposisi
        $qrDari = $this->buildQr("TANDA TANGAN DIGITAL\n"
            . "Nama: " . ($disposisi->dariUser->name ?? '-') . "\n"
            . "Jabatan: " . ($disposisi->dariUser->jabatan->nama_jabatan ?? '-') . "\n"
            . "Tanggal: " . $disposisi->created_at->format('d/m/Y H:i') . "\n"
            . $verifikasiUrl
        );

        // QR untuk Yang Menerima
        $allDisposisis = $surat->disposisis->sortBy('created_at');
        $lastDisposisi = $allDisposisis->last();
        $penerima = $lastDisposisi->kepadaUser ?? $disposisi->kepadaUser;

        $qrKepada = $this->buildQr("TANDA TANGAN DIGITAL\n"
            . "Nama: " . ($penerima->name ?? '-') . "\n"
            . "Jabatan: " . ($penerima->jabatan->nama_jabatan ?? '-') . "\n"
            . "Diterima: " . ($lastDisposisi->selesai_at ? $lastDisposisi->selesai_at->format('d/m/Y H:i') : $lastDisposisi->dibaca_at?->format('d/m/Y H:i') ?? '-') . "\n"
            . $verifikasiUrl
        );

        return [
            'dari'   => $qrDari,
            'kepada' => $qrKepada,
        ];
    }

    /**
     * Build QR code image as base64
     */
    private function buildQr(string $text): string
    {
        $qrCode = new QrCode(
            data: $text,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High, // High untuk bisa ada logo
            size: 200,
            margin: 5,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255),
        );

        $writer = new PngWriter();

        // Logo di tengah QR
        $logoPath = public_path('images/logo-perumda.png');
        $logo = null;

        if (file_exists($logoPath)) {
            $logo = new Logo(
                path: $logoPath,
                resizeToWidth: 50,
                resizeToHeight: 50,
                punchoutBackground: true,
            );
        }

        $result = $writer->write($qrCode, $logo);

        return $result->getDataUri();
    }

    /**
     * Tracking disposisi untuk surat tertentu
     */
    public function tracking(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['disposisis.dariUser', 'disposisis.kepadaUser', 'disposisis.logs.user', 'kategori', 'user']);

        return view('disposisi.tracking', compact('suratMasuk'));
    }
}