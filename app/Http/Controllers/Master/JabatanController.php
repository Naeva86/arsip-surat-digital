<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Jabatan::orderBy('level');

        if ($request->filled('search')) {
            $query->where('nama_jabatan', 'like', '%' . $request->search . '%');
        }

        $jabatans = $query->get();

        return view('master.jabatan.index', compact('jabatans'));
    }

    public function create()
    {
        return view('master.jabatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'level'        => 'required|integer|min:1|max:4',
        ]);
        Jabatan::create($request->only('nama_jabatan', 'level'));
        return redirect()->route('master.jabatan.index')
                         ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        return view('master.jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'level'        => 'required|integer|min:1|max:4',
        ]);
        $jabatan->update($request->only('nama_jabatan', 'level'));
        return redirect()->route('master.jabatan.index')
                         ->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();
        return redirect()->route('master.jabatan.index')
                         ->with('success', 'Jabatan berhasil dihapus.');
    }
}