<?php

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
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['direktur', 'kabag', 'kasubbag', 'admin'])) {
            abort(403);
        }

        $query = Disposisi::with(['suratMasuk.kategori', 'suratMasuk.user', 'dariUser', 'kepadaUser'])
            ->where('kepada_user_id', $user->id)
            ->whereHas('suratMasuk');

        if ($user->role === 'kasubbag') {
            if ($request->filled('status_filter')) {
                $query->where('status', $request->status_filter);
            } else {
                $query->whereIn('status', ['menunggu', 'dibaca', 'selesai']);
            }
        } else {
            $query->whereIn('status', ['menunggu', 'dibaca']);
        }

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

        $disposisi->load([
            'suratMasuk.kategori',
            'suratMasuk.user',
            'suratMasuk.disposisis.dariUser',
            'suratMasuk.disposisis.kepadaUser',
            'dariUser',
        ]);

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

    public function proses(Request $request, Disposisi $disposisi)
    {
        $user = auth()->user();

        if ($disposisi->kepada_user_id !== $user->id) abort(403);

        $surat    = $disposisi->suratMasuk;
        $instruksi = $request->instruksi_disposisi ?? '';

        // ── DIREKTUR ──
        if ($user->role === 'direktur') {

            $keputusan = $request->keputusan;

            // TOLAK
            if ($keputusan === 'ditolak') {
                $request->validate([
                    'catatan_tolak' => 'required|string|max:1000',
                ]);

                $disposisi->update([
                    'status'            => 'selesai',
                    'keputusan'         => 'ditolak',
                    'catatan_penolakan' => $request->catatan_tolak,
                    'selesai_at'        => now(),
                ]);

                $surat->update(['status' => 'ditolak']);

                DisposisiLog::create([
                    'disposisi_id' => $disposisi->id,
                    'user_id'      => $user->id,
                    'aksi'         => 'Surat ditolak oleh Direktur',
                    'keterangan'   => $request->catatan_tolak,
                ]);

                return redirect()->route('disposisi.index')
                                 ->with('success', 'Surat berhasil ditolak dan dikembalikan ke Staff.');
            }

            // SETUJU — kirim ke Kabag
            $request->validate([
                'instruksi_disposisi' => 'required|string',
                'tujuan_bagian_id'    => 'required|exists:bagians,id',
            ], [
                'instruksi_disposisi.required' => 'Instruksi disposisi wajib dipilih.',
                'tujuan_bagian_id.required'    => 'Tujuan bagian wajib dipilih.',
            ]);

            $kabag = User::where('role', 'kabag')
                         ->where('bagian_id', $request->tujuan_bagian_id)
                         ->first();

            if (!$kabag) {
                return redirect()->back()->with('error', 'Tidak ditemukan Kabag di bagian tersebut.');
            }

            $disposisi->update([
                'status'              => 'selesai',
                'keputusan'           => 'setuju',
                'instruksi_disposisi' => $instruksi,
                'tujuan_bagian_id'    => $request->tujuan_bagian_id,
                'selesai_at'          => now(),
            ]);

            $newDisposisi = Disposisi::create([
                'surat_masuk_id'   => $surat->id,
                'dari_user_id'     => $user->id,
                'kepada_user_id'   => $kabag->id,
                'tujuan_bagian_id' => $request->tujuan_bagian_id,
                'level'            => 2,
                'isi_disposisi'    => $instruksi,
                'status'           => 'menunggu',
            ]);

            $surat->update(['status' => 'proses_disposisi']);

            DisposisiLog::create([
                'disposisi_id' => $newDisposisi->id,
                'user_id'      => $user->id,
                'aksi'         => 'Disposisi dikirim ke ' . $kabag->name,
                'keterangan'   => 'Instruksi: ' . $instruksi,
            ]);

            return redirect()->route('disposisi.index')
                             ->with('success', 'Disposisi berhasil dikirim ke ' . $kabag->name);
        }

        // ── KABAG ──
        if ($user->role === 'kabag') {

            $aksi = $request->aksi_kabag;

            // SELESAI di Kabag
            if ($aksi === 'selesai') {
                $request->validate([
                    'instruksi_disposisi' => 'required|string',
                ], [
                    'instruksi_disposisi.required' => 'Instruksi disposisi wajib dipilih.',
                ]);

                $disposisi->update([
                    'status'              => 'selesai',
                    'keputusan'           => 'setuju',
                    'instruksi_disposisi' => $instruksi,
                    'selesai_at'          => now(),
                ]);

                $surat->update(['status' => 'selesai']);

                DisposisiLog::create([
                    'disposisi_id' => $disposisi->id,
                    'user_id'      => $user->id,
                    'aksi'         => 'Disposisi diselesaikan oleh ' . $user->name,
                    'keterangan'   => $instruksi,
                ]);

                return redirect()->route('disposisi.index')
                                 ->with('success', 'Disposisi selesai.');
            }

            // LANJUTKAN ke Kasubag
            if ($aksi === 'lanjutkan') {
                $request->validate([
                    'instruksi_disposisi'  => 'required|string',
                    'kepada_kasubag_id'    => 'required|exists:users,id',
                ], [
                    'instruksi_disposisi.required' => 'Instruksi disposisi wajib dipilih.',
                    'kepada_kasubag_id.required'   => 'Kasubag tujuan wajib dipilih.',
                ]);

                $kasubag = User::findOrFail($request->kepada_kasubag_id);

                $disposisi->update([
                    'status'              => 'selesai',
                    'keputusan'           => 'setuju',
                    'instruksi_disposisi' => $instruksi,
                    'selesai_at'          => now(),
                ]);

                $newDisposisi = Disposisi::create([
                    'surat_masuk_id'   => $surat->id,
                    'dari_user_id'     => $user->id,
                    'kepada_user_id'   => $kasubag->id,
                    'tujuan_bagian_id' => $kasubag->bagian_id,
                    'level'            => 3,
                    'isi_disposisi'    => $instruksi,
                    'status'           => 'menunggu',
                ]);

                $surat->update(['status' => 'selesai']);

                DisposisiLog::create([
                    'disposisi_id' => $newDisposisi->id,
                    'user_id'      => $user->id,
                    'aksi'         => 'Disposisi diteruskan ke ' . $kasubag->name,
                    'keterangan'   => $instruksi,
                ]);

                return redirect()->route('disposisi.index')
                                 ->with('success', 'Disposisi berhasil diteruskan ke ' . $kasubag->name);
            }
        }

        abort(403);
    }

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

        $qrData = $this->generateQrCode($disposisi);

        $pdf = Pdf::loadView('pdf.lembar-disposisi', compact('disposisi', 'qrData'))
                   ->setPaper('a4', 'portrait');

        $filename = 'Disposisi-' . str_replace('/', '-', $disposisi->suratMasuk->no_agenda) . '.pdf';

        return $pdf->stream($filename);
    }

    private function generateQrCode(Disposisi $disposisi): array
    {
        $surat = $disposisi->suratMasuk;
        $allDisposisis = $surat->disposisis->sortBy('created_at');

        $dispoLevel1 = $allDisposisis->where('level', 1)->where('keputusan', 'setuju')->first();
        $dispoLevel2 = $allDisposisis->where('level', 2)->first();

        $verifikasiUrl = url('/verifikasi/disposisi/' . $disposisi->id . '?hash=' . md5($disposisi->id . $surat->no_agenda . $disposisi->created_at));

        $direktur = $dispoLevel1 ? $dispoLevel1->kepadaUser : $disposisi->dariUser;
        $qrDari = $this->buildQr(
            "TTD DIGITAL\n"
            . "Nama: " . ($direktur->name ?? '-') . "\n"
            . "Jabatan: " . ($direktur->jabatan->nama_jabatan ?? '-') . "\n"
            . "No Agenda: " . $surat->no_agenda . "\n"
            . "Disposisi: " . ($dispoLevel1->instruksi_disposisi ?? '-') . "\n"
            . $verifikasiUrl
        );

        $kabag = $dispoLevel2 ? $dispoLevel2->kepadaUser : $disposisi->kepadaUser;
        $qrKepada = $this->buildQr(
            "TTD DIGITAL\n"
            . "Nama: " . ($kabag->name ?? '-') . "\n"
            . "Jabatan: " . ($kabag->jabatan->nama_jabatan ?? '-') . "\n"
            . "Bagian: " . ($kabag->bagian->nama_bagian ?? '-') . "\n"
            . "No Agenda: " . $surat->no_agenda . "\n"
            . $verifikasiUrl
        );

        return [
            'dari'   => $qrDari,
            'kepada' => $qrKepada,
        ];
    }

    private function buildQr(string $text): string
    {
        $qrCode = new QrCode(
            data: $text,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 200,
            margin: 5,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255),
        );

        $writer = new PngWriter();

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

    public function tracking(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['disposisis.dariUser', 'disposisis.kepadaUser', 'disposisis.logs.user', 'kategori', 'user']);

        return view('disposisi.tracking', compact('suratMasuk'));
    }
}