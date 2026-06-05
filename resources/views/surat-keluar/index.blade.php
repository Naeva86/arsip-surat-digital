@extends('layouts.app')
@section('title', 'Surat Keluar')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Surat Keluar</h2>
        <p class="text-sm text-gray-400 mt-0.5">Kelola semua surat keluar</p>
    </div>
    <div class="flex items-center gap-2">

        {{-- Search --}}
        <form method="GET" action="{{ route('surat-masuk.index') }}" class="relative">
            @foreach(request()->except(['search','page']) as $key => $val)
            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari surat..."
                   class="w-48 border border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        </form>

        {{-- Tombol Filter --}}
        <button onclick="toggleFilter()"
                class="relative inline-flex items-center gap-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            Filter
            @php $activeCount = count(array_filter(request()->only(['search','sifat','kategori_id','dari_tanggal','sampai_tanggal']))); @endphp
            @if($activeCount > 0)
            <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-blue-600 text-white text-xs rounded-full flex items-center justify-center font-bold leading-none">{{ $activeCount }}</span>
            @endif
        </button>

        <a href="{{ route('surat-keluar.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Surat
        </a>
    </div>
</div>

{{-- Filter Active Pills --}}
@if($activeCount > 0)
<div class="flex flex-wrap items-center gap-2 mb-4">
    <span class="text-xs text-gray-400">Filter aktif:</span>
    @if(request('search'))
    <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2.5 py-1 rounded-full">
        "{{ request('search') }}"
        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="hover:opacity-70 font-bold">×</a>
    </span>
    @endif
    @if(request('sifat'))
    <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2.5 py-1 rounded-full">
        Sifat: {{ ucfirst(request('sifat')) }}
        <a href="{{ request()->fullUrlWithQuery(['sifat' => null]) }}" class="hover:opacity-70 font-bold">×</a>
    </span>
    @endif
    @if(request('dari_tanggal') || request('sampai_tanggal'))
    <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2.5 py-1 rounded-full">
        {{ request('dari_tanggal') }} – {{ request('sampai_tanggal') }}
        <a href="{{ request()->fullUrlWithQuery(['dari_tanggal' => null, 'sampai_tanggal' => null]) }}" class="hover:opacity-70 font-bold">×</a>
    </span>
    @endif
    <a href="{{ route('surat-keluar.index') }}" class="text-xs text-red-500 hover:text-red-700 hover:underline ml-1">Hapus semua</a>
</div>
@endif

{{-- Overlay --}}
<div id="filter-overlay" onclick="toggleFilter()"
     class="fixed inset-0 bg-black/40 z-40 hidden"></div>

{{-- Filter Slide-over Panel --}}
<div id="filter-panel"
     class="fixed top-0 right-0 h-full w-80 bg-white dark:bg-gray-800 shadow-2xl z-50 flex flex-col filter-slide">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
        <div>
            <h3 class="font-semibold text-gray-800 dark:text-white text-sm">Filter Surat Keluar</h3>
            <p class="text-xs text-gray-400 mt-0.5">Saring data sesuai kebutuhan</p>
        </div>
        <button onclick="toggleFilter()"
                class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <form method="GET" action="{{ route('surat-keluar.index') }}" class="flex flex-col flex-1 overflow-hidden">
        <div class="flex-1 overflow-y-auto px-5 py-5 space-y-5">

            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Pencarian</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nomor, perihal, penerima..."
                           class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Sifat Surat</label>
                <div class="space-y-1.5">
                    @foreach(['' => 'Semua Sifat', 'biasa' => 'Biasa', 'penting' => 'Penting', 'rahasia' => 'Rahasia', 'urgent' => 'Urgent'] as $val => $label)
                    <label class="filter-radio-label flex items-center gap-3 px-3 py-2.5 border rounded-lg cursor-pointer transition {{ request('sifat') === $val ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-500' : 'border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                        <input type="radio" name="sifat" value="{{ $val }}" {{ request('sifat') === $val ? 'checked' : '' }} class="hidden">
                        <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0 {{ request('sifat') === $val ? 'border-blue-500' : 'border-gray-300 dark:border-gray-500' }}">
                            @if(request('sifat') === $val)<span class="w-2 h-2 rounded-full bg-blue-500"></span>@endif
                        </span>
                        <span class="text-sm {{ request('sifat') === $val ? 'text-blue-600 dark:text-blue-400 font-medium' : 'text-gray-600 dark:text-gray-300' }}">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Kategori</label>
                <select name="kategori_id"
                        class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Rentang Tanggal</label>
                <div class="space-y-2">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Dari tanggal</p>
                        <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}"
                               class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Sampai tanggal</p>
                        <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}"
                               class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

        </div>

        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700 flex gap-2 flex-shrink-0">
            <a href="{{ route('surat-keluar.index') }}"
               class="flex-1 text-center text-sm text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 py-2.5 rounded-lg transition font-medium">
                Reset
            </a>
            <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2.5 rounded-lg transition">
                Terapkan
            </button>
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-10">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Nomor Surat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Perihal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Penerima</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Sifat</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($suratKeluars as $i => $surat)
                @php
                    $sifatColor = match($surat->sifat) {
                        'urgent'  => 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                        'penting' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400',
                        'rahasia' => 'bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
                        default   => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                    };
                    $sifatColorModal = match($surat->sifat) {
                        'urgent'  => 'bg-red-50 text-red-600',
                        'penting' => 'bg-yellow-50 text-yellow-600',
                        'rahasia' => 'bg-purple-50 text-purple-600',
                        default   => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $suratKeluars->firstItem() + $i }}</td>
                    <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-300">{{ $surat->nomor_surat }}</td>
                    <td class="px-4 py-3 max-w-xs">
                        <div class="font-medium text-gray-800 dark:text-white truncate">{{ Str::limit($surat->judul_surat, 35) }}</div>
                        @if($surat->kategori)
                        <div class="text-xs text-gray-400 mt-0.5">{{ $surat->kategori->nama_kategori }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $surat->penerima }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $sifatColor }}">{{ ucfirst($surat->sifat) }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">

                            {{-- Detail: buka modal inline --}}
                            <button onclick="document.getElementById('modal-{{ $surat->id }}').classList.remove('hidden')"
                                    title="Detail"
                                    class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>

                            {{-- Edit --}}
                            <a href="{{ route('surat-keluar.edit', $surat) }}" title="Edit"
                               class="p-1.5 text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            {{-- Hapus --}}
                            <form action="{{ route('surat-keluar.destroy', $surat) }}" method="POST"
                                  data-konfirmasi
                                  data-tipe="hapus"
                                  data-judul="Hapus Surat?"
                                  data-pesan="Surat ini akan dihapus."
                                  data-label-ok="Ya, Hapus"
                                  class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" title="Hapus"
                                        class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- MODAL DETAIL INLINE — pure Blade, tidak ada JS data mapping --}}
                <tr>
                    <td colspan="7" class="p-0 border-0">
                        <div id="modal-{{ $surat->id }}"
                             class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
                             onclick="if(event.target===this) this.classList.add('hidden')">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">

                                {{-- Header --}}
                                <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 flex-shrink-0">
                                    <div>
                                        <h4 class="font-semibold text-gray-800 dark:text-white">Detail Surat Keluar</h4>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $surat->nomor_surat }}</p>
                                    </div>
                                    <button onclick="document.getElementById('modal-{{ $surat->id }}').classList.add('hidden')"
                                            class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 hover:text-gray-600 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Konten --}}
                                <div class="flex-1 overflow-y-auto px-6 py-5">
                                    <div class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Nomor Surat</p>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $surat->nomor_surat }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Penerima</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $surat->penerima }}</p>
                                        </div>
                                        <div class="col-span-2">
                                            <p class="text-xs text-gray-400 mb-0.5">Perihal</p>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $surat->judul_surat }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Tanggal Surat</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $surat->tanggal_surat->format('d/m/Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Tanggal Arsip</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $surat->tanggal_arsip->format('d/m/Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Sifat</p>
                                            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $sifatColorModal }}">
                                                {{ ucfirst($surat->sifat) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Kategori</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $surat->kategori->nama_kategori ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Dibuat Oleh</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $surat->user->name ?? '-' }}</p>
                                        </div>
                                        @if($surat->keterangan)
                                        <div class="col-span-2">
                                            <p class="text-xs text-gray-400 mb-0.5">Keterangan</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $surat->keterangan }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Footer --}}
                                <div class="px-6 py-4 border-t dark:border-gray-700 flex gap-2 flex-shrink-0">
                                    @if($surat->file_path)
                                    <a href="{{ Storage::url($surat->file_path) }}" target="_blank"
                                       class="inline-flex items-center gap-2 text-sm bg-green-100 text-green-700 hover:bg-green-200 px-4 py-2 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Lihat File
                                    </a>
                                    @endif
                                    <a href="{{ route('surat-keluar.edit', $surat) }}"
                                       class="inline-flex items-center gap-2 text-sm bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-4 py-2 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <button onclick="document.getElementById('modal-{{ $surat->id }}').classList.add('hidden')"
                                            class="text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white text-gray-700 px-4 py-2 rounded-lg transition ml-auto">
                                        Tutup
                                    </button>
                                </div>

                            </div>
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <p class="text-sm">Belum ada surat keluar</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($suratKeluars->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
        {{ $suratKeluars->links() }}
    </div>
    @endif
</div>

<script>
function toggleFilter() {
    var panel   = document.getElementById('filter-panel');
    var overlay = document.getElementById('filter-overlay');

    if (panel.classList.contains('filter-open')) {
        panel.classList.remove('filter-open');
        overlay.classList.add('hidden');
    } else {
        panel.classList.add('filter-open');
        overlay.classList.remove('hidden');
    }
}

document.querySelectorAll('.filter-radio-label input[type="radio"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var name = this.name;
        document.querySelectorAll('.filter-radio-label input[name="' + name + '"]').forEach(function(r) {
            var lbl  = r.closest('.filter-radio-label');
            var span = lbl.querySelector('span:last-child');
            if (r.checked) {
                lbl.classList.add('border-blue-500', 'bg-blue-50', 'dark:border-blue-500');
                lbl.classList.remove('border-gray-200', 'dark:border-gray-600');
                if (span) { span.classList.add('text-blue-600', 'font-medium'); span.classList.remove('text-gray-600', 'dark:text-gray-300'); }
            } else {
                lbl.classList.remove('border-blue-500', 'bg-blue-50', 'dark:border-blue-500');
                lbl.classList.add('border-gray-200', 'dark:border-gray-600');
                if (span) { span.classList.remove('text-blue-600', 'font-medium'); span.classList.add('text-gray-600', 'dark:text-gray-300'); }
            }
        });
    });
});
</script>

<style>
    /* Filter slide-over */
    .filter-slide {
        transform: translateX(100%);
        transition: transform 0.3s ease;
    }
    .filter-slide.filter-open {
        transform: translateX(0%);
    }
</style>

@endsection