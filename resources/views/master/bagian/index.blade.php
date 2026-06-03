@extends('layouts.app')
@section('title', 'Bagian')

@section('content')

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Kelola Bagian</h2>
        <p class="text-sm text-gray-400 mt-0.5">Struktur bagian dan sub bagian organisasi</p>
    </div>
    <div class="flex items-center gap-2">
        <form method="GET" action="{{ route('master.bagian.index') }}" class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari bagian..."
                   class="w-48 border border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        </form>
        <a href="{{ route('master.bagian.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah
        </a>
    </div>
</div>

{{-- Statistik --}}
<div class="grid grid-cols-3 gap-3 mb-5">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $allBagians->count() }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Total</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
        <p class="text-2xl font-bold text-blue-600">{{ $parentBagians->count() }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Bagian Utama</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
        <p class="text-2xl font-bold text-teal-600">{{ $allBagians->whereNotNull('parent_id')->count() }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Sub Bagian</p>
    </div>
</div>

{{-- Card per Bagian Utama --}}
@forelse($parentBagians as $parent)
@php
    $children = $allBagians->where('parent_id', $parent->id)->sortBy('nama_bagian');
    $kabag = \App\Models\User::where('role', 'kabag')->where('bagian_id', $parent->id)->first();
@endphp
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-4">

    {{-- Header Bagian --}}
    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">{{ $parent->nama_bagian }}</h3>
                <div class="flex items-center gap-2 mt-0.5">
                    @if($kabag)
                    <span class="text-xs text-gray-400">Kabag: <span class="text-gray-600 dark:text-gray-300 font-medium">{{ $kabag->name }}</span></span>
                    @else
                    <span class="text-xs text-gray-400">Kabag: <span class="text-red-400">Belum ditentukan</span></span>
                    @endif
                    <span class="text-xs text-gray-300">•</span>
                    <span class="text-xs bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 px-2 py-0.5 rounded-full font-medium">{{ $children->count() }} sub bagian</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-1">
            <a href="{{ route('master.bagian.edit', $parent) }}" title="Edit Bagian"
               class="p-1.5 text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </a>
            @if($children->count() === 0)
            <form action="{{ route('master.bagian.destroy', $parent) }}" method="POST" class="inline"
                  data-konfirmasi data-tipe="hapus" data-judul="Hapus Bagian?" data-pesan="{{ $parent->nama_bagian }} akan dihapus." data-label-ok="Ya, Hapus">
                @csrf @method('DELETE')
                <button type="submit" title="Hapus" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Sub Bagian List --}}
    @if($children->count() > 0)
    <div class="divide-y divide-gray-100 dark:divide-gray-700">
        @foreach($children as $child)
        @php $kasubag = \App\Models\User::where('role', 'kasubbag')->where('bagian_id', $child->id)->first(); @endphp
        <div class="px-5 py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 pl-4">
                    <span class="text-gray-300 dark:text-gray-600 text-sm">└</span>
                    <div class="w-7 h-7 rounded-md bg-teal-50 dark:bg-teal-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $child->nama_bagian }}</p>
                    @if($kasubag)
                    <p class="text-xs text-gray-400 mt-0.5">Kasubag: {{ $kasubag->name }}</p>
                    @else
                    <p class="text-xs text-red-300 mt-0.5">Kasubag: Belum ditentukan</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-1">
                <a href="{{ route('master.bagian.edit', $child) }}" title="Edit"
                   class="p-1.5 text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form action="{{ route('master.bagian.destroy', $child) }}" method="POST" class="inline"
                      data-konfirmasi data-tipe="hapus" data-judul="Hapus Sub Bagian?" data-pesan="{{ $child->nama_bagian }} akan dihapus." data-label-ok="Ya, Hapus">
                    @csrf @method('DELETE')
                    <button type="submit" title="Hapus" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="px-5 py-6 text-center text-gray-400">
        <p class="text-xs">Belum ada sub bagian</p>
    </div>
    @endif

</div>
@empty
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-16 text-center text-gray-400">
    <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    <p class="text-sm">Belum ada data bagian</p>
</div>
@endforelse

@endsection