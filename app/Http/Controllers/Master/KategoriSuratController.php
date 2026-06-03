<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\KategoriSurat;
use Illuminate\Http\Request;

class KategoriSuratController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriSurat::orderBy('nama_kategori');

        if ($request->filled('search')) {
            $query->where('nama_kategori', 'like', '%' . $request->search . '%');
        }

        $kategoris = $query->get();

        return view('master.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('master.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_surats',
        ]);
        KategoriSurat::create($request->only('nama_kategori'));
        return redirect()->route('master.kategori.index')
                         ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(KategoriSurat $kategori)
    {
        return view('master.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, KategoriSurat $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_surats,nama_kategori,' . $kategori->id,
        ]);
        $kategori->update($request->only('nama_kategori'));
        return redirect()->route('master.kategori.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriSurat $kategori)
    {
        $kategori->delete();
        return redirect()->route('master.kategori.index')
                         ->with('success', 'Kategori berhasil dihapus.');
    }
}