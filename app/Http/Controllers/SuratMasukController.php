<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\KategoriSurat;
use App\Models\Disposisi;
use App\Models\DisposisiLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'staff'])) {
            abort(403);
        }

        // Tambah disposisis untuk cek di view (tracking count, penolakan)
        $query = SuratMasuk::with(['kategori', 'user', 'disposisis.dariUser', 'disposisis.kepadaUser'])
                            ->latest('tanggal_arsip');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('no_agenda', 'like', "%$s%")
                  ->orWhere('nomor_surat', 'like', "%$s%")
                  ->orWhere('judul_surat', 'like', "%$s%")
                  ->orWhere('pengirim', 'like', "%$s%");
            });
        }
        if ($request->filled('status'))        $query->where('status', $request->status);
        if ($request->filled('sifat'))         $query->where('sifat', $request->sifat);
        if ($request->filled('kategori_id'))   $query->where('kategori_id', $request->kategori_id);
        if ($request->filled('dari_tanggal'))  $query->whereDate('tanggal_surat', '>=', $request->dari_tanggal);
        if ($request->filled('sampai_tanggal'))$query->whereDate('tanggal_surat', '<=', $request->sampai_tanggal);

        $suratMasuks = $query->paginate(10)->withQueryString();
        $kategoris   = KategoriSurat::orderBy('nama_kategori')->get();

        return view('surat-masuk.index', compact('suratMasuks', 'kategoris'));
    }

    public function create()
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) abort(403);

        $noAgenda  = SuratMasuk::generateNoAgenda();
        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();

        return view('surat-masuk.create', compact('noAgenda', 'kategoris'));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) abort(403);

        $validated = $request->validate([
            'nomor_surat'       => 'required|string|max:100',
            'judul_surat'       => 'required|string|max:255',
            'pengirim'          => 'required|string|max:255',
            'tanggal_surat'     => 'required|date',
            'tanggal_arsip'     => 'required|date',
            'sifat'             => 'required|in:biasa,penting,rahasia,urgent',
            'kategori_id'       => 'required|exists:kategori_surats,id',
            'file_path'         => 'required|file|mimes:pdf|max:10240',
            'diagendakan_nomor' => 'nullable|string|max:100',
            'keterangan'        => 'nullable|string|max:500',
        ]);

        $validated['no_agenda'] = SuratMasuk::generateNoAgenda();
        $validated['user_id']   = auth()->id();
        $validated['status']    = 'menunggu_direktur';

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('surat-masuk', 'public');
        }

        $surat = SuratMasuk::create($validated);

        // Otomatis buat disposisi ke Direktur
        $direktur = User::where('role', 'direktur')->first();
        if ($direktur) {
            $disposisi = Disposisi::create([
                'surat_masuk_id' => $surat->id,
                'dari_user_id'   => auth()->id(),
                'kepada_user_id' => $direktur->id,
                'level'          => 1,
                'isi_disposisi'  => 'Surat masuk baru, menunggu persetujuan Direktur.',
                'status'         => 'menunggu',
            ]);

            DisposisiLog::create([
                'disposisi_id' => $disposisi->id,
                'user_id'      => auth()->id(),
                'aksi'         => 'Surat masuk dikirim otomatis ke Direktur',
                'keterangan'   => 'Surat diinput oleh ' . auth()->user()->name,
            ]);
        }

        return redirect()->route('surat-masuk.index')
                         ->with('success', 'Surat masuk berhasil disimpan dan dikirim ke Direktur.');
    }

    /**
     * Cek apakah surat boleh diedit oleh user ini
     */
    private function canEdit(SuratMasuk $surat): bool
    {
        $user = auth()->user();

        // Admin selalu boleh
        if ($user->role === 'admin') return true;

        // Staff hanya boleh edit jika status ditolak
        // (status 'baru' tidak pernah terjadi karena langsung 'menunggu_direktur')
        if ($user->role === 'staff' && $surat->status === 'ditolak') return true;

        return false;
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) abort(403);

        if (!$this->canEdit($suratMasuk)) {
            return redirect()->route('surat-masuk.index')
                             ->with('error', 'Surat tidak dapat diedit karena sudah dalam proses disposisi.');
        }

        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        return view('surat-masuk.edit', compact('suratMasuk', 'kategoris'));
    }

    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) abort(403);

        if (!$this->canEdit($suratMasuk)) {
            return redirect()->route('surat-masuk.index')
                             ->with('error', 'Surat tidak dapat diedit.');
        }

        $validated = $request->validate([
            'nomor_surat'       => 'required|string|max:100',
            'judul_surat'       => 'required|string|max:255',
            'pengirim'          => 'required|string|max:255',
            'tanggal_surat'     => 'required|date',
            'tanggal_arsip'     => 'required|date',
            'sifat'             => 'required|in:biasa,penting,rahasia,urgent',
            'kategori_id'       => 'required|exists:kategori_surats,id',
            'file_path'         => 'nullable|file|mimes:pdf|max:10240',
            'diagendakan_nomor' => 'nullable|string|max:100',
            'keterangan'        => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('file_path')) {
            if ($suratMasuk->file_path) Storage::disk('public')->delete($suratMasuk->file_path);
            $validated['file_path'] = $request->file('file_path')->store('surat-masuk', 'public');
        }

        // Jika ditolak → kirim ulang ke direktur
        if ($suratMasuk->status === 'ditolak') {
            $validated['status'] = 'menunggu_direktur';

            $direktur = User::where('role', 'direktur')->first();
            if ($direktur) {
                $disposisi = Disposisi::create([
                    'surat_masuk_id' => $suratMasuk->id,
                    'dari_user_id'   => auth()->id(),
                    'kepada_user_id' => $direktur->id,
                    'level'          => 1,
                    'isi_disposisi'  => 'Surat diperbaiki dan dikirim ulang ke Direktur.',
                    'status'         => 'menunggu',
                ]);

                DisposisiLog::create([
                    'disposisi_id' => $disposisi->id,
                    'user_id'      => auth()->id(),
                    'aksi'         => 'Surat dikirim ulang ke Direktur setelah diperbaiki',
                    'keterangan'   => 'Diperbaiki oleh ' . auth()->user()->name,
                ]);
            }
        }

        $suratMasuk->update($validated);

        return redirect()->route('surat-masuk.index')
                         ->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) abort(403);

        if (!$this->canEdit($suratMasuk)) {
            return redirect()->route('surat-masuk.index')
                             ->with('error', 'Surat tidak dapat dihapus karena sudah dalam proses disposisi.');
        }

        if ($suratMasuk->file_path) Storage::disk('public')->delete($suratMasuk->file_path);
        $suratMasuk->delete();

        return redirect()->route('surat-masuk.index')
                         ->with('success', 'Surat masuk berhasil dihapus.');
    }

    public function show(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['kategori', 'user', 'disposisis.dariUser', 'disposisis.kepadaUser', 'disposisis.logs']);
        return view('surat-masuk.show', compact('suratMasuk'));
    }
}