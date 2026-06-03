<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\KategoriSurat;
use App\Models\Bagian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratKeluar::with(['user', 'kategori', 'bagian'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%$search%")
                ->orWhere('judul_surat', 'like', "%$search%")
                ->orWhere('penerima', 'like', "%$search%");
            });
        }

        if ($request->filled('sifat')) {
            $query->where('sifat', $request->sifat);
        }

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal_surat', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal_surat', '<=', $request->sampai_tanggal);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $suratKeluars = $query->paginate(10)->withQueryString();
        $kategoris    = \App\Models\KategoriSurat::orderBy('nama_kategori')->get();

        return view('surat-keluar.index', compact('suratKeluars', 'kategoris'));
    }

    public function create()
    {
        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        $bagians   = Bagian::orderBy('urutan')->get();
        return view('surat-keluar.create', compact('kategoris', 'bagians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat'   => 'required|string|max:255|unique:surat_keluars,nomor_surat',
            'judul_surat'   => 'required|string|max:255',
            'penerima'      => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tanggal_arsip' => 'required|date',
            'sifat'         => 'required|in:biasa,penting,rahasia',
            'kategori_id'   => 'nullable|exists:kategori_surats,id',
            'bagian_id'     => 'nullable|exists:bagians,id',
            'keterangan'    => 'nullable|string',
            'file_path'     => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = $request->only([
            'nomor_surat', 'judul_surat', 'penerima',
            'tanggal_surat', 'tanggal_arsip', 'sifat',
            'kategori_id', 'bagian_id', 'keterangan',
        ]);

        $data['user_id'] = auth()->id();

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')
                ->store('surat-keluar/' . date('Y/m'), 'public');
        }

        SuratKeluar::create($data);

        return redirect()->route('surat-keluar.index')
                         ->with('success', 'Surat keluar berhasil ditambahkan.');
    }

    public function show(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['user', 'kategori', 'bagian']);
        return view('surat-keluar.show', compact('suratKeluar'));
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        $kategoris = KategoriSurat::orderBy('nama_kategori')->get();
        $bagians   = Bagian::orderBy('urutan')->get();
        return view('surat-keluar.edit', compact('suratKeluar', 'kategoris', 'bagians'));
    }

    public function update(Request $request, SuratKeluar $suratKeluar)
    {
        $request->validate([
            'nomor_surat'   => 'required|string|max:255|unique:surat_keluars,nomor_surat,' . $suratKeluar->id,
            'judul_surat'   => 'required|string|max:255',
            'penerima'      => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tanggal_arsip' => 'required|date',
            'sifat'         => 'required|in:biasa,penting,rahasia',
            'kategori_id'   => 'nullable|exists:kategori_surats,id',
            'bagian_id'     => 'nullable|exists:bagians,id',
            'keterangan'    => 'nullable|string',
            'file_path'     => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = $request->only([
            'nomor_surat', 'judul_surat', 'penerima',
            'tanggal_surat', 'tanggal_arsip', 'sifat',
            'kategori_id', 'bagian_id', 'keterangan',
        ]);

        if ($request->hasFile('file_path')) {
            if ($suratKeluar->file_path) {
                Storage::disk('public')->delete($suratKeluar->file_path);
            }
            $data['file_path'] = $request->file('file_path')
                ->store('surat-keluar/' . date('Y/m'), 'public');
        }

        $suratKeluar->update($data);

        return redirect()->route('surat-keluar.index')
                         ->with('success', 'Surat keluar berhasil diperbarui.');
    }

    public function destroy(SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->file_path) {
            Storage::disk('public')->delete($suratKeluar->file_path);
        }
        $suratKeluar->delete();

        return redirect()->route('surat-keluar.index')
                         ->with('success', 'Surat keluar berhasil dihapus.');
    }
}