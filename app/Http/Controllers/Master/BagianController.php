<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Bagian;
use Illuminate\Http\Request;

class BagianController extends Controller
{
    public function index(Request $request)
    {
        $query = Bagian::with(['parent', 'children']);

        if ($request->filled('search')) {
            $query->where('nama_bagian', 'like', '%' . $request->search . '%');
        }

        $allBagians = $query->get();
        $parentBagians = $allBagians->whereNull('parent_id')->sortBy('nama_bagian');

        return view('master.bagian.index', compact('allBagians', 'parentBagians'));
    }

    public function create()
    {
        $parents = Bagian::whereNull('parent_id')->orderBy('nama_bagian')->get();
        return view('master.bagian.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bagian' => 'required|string|max:255|unique:bagians,nama_bagian',
            'parent_id'   => 'nullable|exists:bagians,id',
        ]);

        Bagian::create($request->only(['nama_bagian', 'parent_id']));

        return redirect()->route('master.bagian.index')
                         ->with('success', 'Bagian berhasil ditambahkan.');
    }

    public function edit(Bagian $bagian)
    {
        $parents = Bagian::whereNull('parent_id')
                         ->where('id', '!=', $bagian->id)
                         ->orderBy('nama_bagian')
                         ->get();

        return view('master.bagian.edit', compact('bagian', 'parents'));
    }

    public function update(Request $request, Bagian $bagian)
    {
        $request->validate([
            'nama_bagian' => 'required|string|max:255|unique:bagians,nama_bagian,' . $bagian->id,
            'parent_id'   => 'nullable|exists:bagians,id',
        ]);

        $bagian->update($request->only(['nama_bagian', 'parent_id']));

        return redirect()->route('master.bagian.index')
                         ->with('success', 'Bagian berhasil diperbarui.');
    }

    public function destroy(Bagian $bagian)
    {
        if ($bagian->children()->count() > 0) {
            return redirect()->route('master.bagian.index')
                             ->with('error', 'Tidak dapat menghapus bagian yang memiliki sub bagian.');
        }

        $bagian->delete();

        return redirect()->route('master.bagian.index')
                         ->with('success', 'Bagian berhasil dihapus.');
    }
}