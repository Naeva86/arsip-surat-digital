@extends('layouts.app')
@section('title', 'Lemari Arsip Surat')

@section('content')

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Lemari Arsip Surat</h2>
        <p class="text-sm text-gray-400 mt-0.5">Arsip surat masuk yang sudah selesai dan surat keluar</p>
    </div>
    <div class="flex items-center gap-2">
        <form method="GET" action="{{ route('arsip.index') }}" class="relative">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari surat..."
                   class="w-56 border border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        </form>
    </div>
</div>

{{-- Tab --}}
<div class="flex border-b border-gray-200 dark:border-gray-700 mb-5">
    <a href="{{ route('arsip.index', ['tab' => 'masuk']) }}"
       class="px-5 py-3 text-sm font-medium border-b-2 transition {{ $tab === 'masuk' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
        Surat Masuk
        <span class="ml-1.5 bg-blue-100 text-blue-600 text-xs px-1.5 py-0.5 rounded-full">{{ $suratMasuks->total() }}</span>
    </a>
    <a href="{{ route('arsip.index', ['tab' => 'keluar']) }}"
       class="px-5 py-3 text-sm font-medium border-b-2 transition {{ $tab === 'keluar' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
        Surat Keluar
        <span class="ml-1.5 bg-gray-100 text-gray-600 text-xs px-1.5 py-0.5 rounded-full">{{ $suratKeluars->total() }}</span>
    </a>
</div>

{{-- ═══════════════════════════════════════ --}}
{{-- TAB SURAT MASUK                         --}}
{{-- ═══════════════════════════════════════ --}}
@if($tab === 'masuk')
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-10">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">No Agenda</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Perihal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Pengirim</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($suratMasuks as $i => $surat)
                @php
                    $sifatColorModal = match($surat->sifat) {
                        'urgent'  => 'bg-red-50 text-red-600',
                        'penting' => 'bg-yellow-50 text-yellow-600',
                        'rahasia' => 'bg-purple-50 text-purple-600',
                        default   => 'bg-gray-100 text-gray-500',
                    };
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $suratMasuks->firstItem() + $i }}</td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">{{ $surat->no_agenda }}</span>
                    </td>
                    <td class="px-4 py-3 max-w-xs">
                        <div class="font-medium text-gray-800 dark:text-white truncate">{{ Str::limit($surat->judul_surat, 40) }}</div>
                        @if($surat->kategori)
                        <div class="text-xs text-gray-400 mt-0.5">{{ $surat->kategori->nama_kategori }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $surat->pengirim }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium bg-green-50 text-green-600">Selesai</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">

                            {{-- Detail popup --}}
                            <button onclick="document.getElementById('arsip-modal-{{ $surat->id }}').classList.remove('hidden')"
                                    title="Detail"
                                    class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>

                            {{-- Tracking --}}
                            <a href="{{ route('disposisi.tracking', $surat) }}" title="Tracking"
                               class="p-1.5 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </a>

                            {{-- Cetak Disposisi --}}
                            @php $lastDisposisi = $surat->disposisis->sortByDesc('created_at')->first(); @endphp
                            @if($lastDisposisi)
                            <a href="{{ route('disposisi.cetak', $lastDisposisi) }}" target="_blank" title="Cetak Disposisi"
                               class="p-1.5 text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                            </a>
                            @endif

                            {{-- Lihat File --}}
                            @if($surat->file_path)
                            <a href="{{ Storage::url($surat->file_path) }}" target="_blank" title="Lihat File"
                               class="p-1.5 text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </a>
                            @endif

                        </div>
                    </td>
                </tr>

                {{-- Modal Detail Surat Masuk --}}
                <tr>
                    <td colspan="7" class="p-0 border-0">
                        <div id="arsip-modal-{{ $surat->id }}"
                             class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
                             onclick="if(event.target===this) this.classList.add('hidden')">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">

                                <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 flex-shrink-0">
                                    <div>
                                        <h4 class="font-semibold text-gray-800 dark:text-white">Detail Surat Masuk</h4>
                                        <p class="text-xs text-gray-400 mt-0.5">No Agenda: {{ $surat->no_agenda }}</p>
                                    </div>
                                    <button onclick="document.getElementById('arsip-modal-{{ $surat->id }}').classList.add('hidden')"
                                            class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 hover:text-gray-600 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="flex-1 overflow-y-auto px-6 py-5">
                                    <div class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Nomor Surat</p>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $surat->nomor_surat }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Pengirim</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $surat->pengirim }}</p>
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
                                            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $sifatColorModal }}">{{ ucfirst($surat->sifat) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Status</p>
                                            <span class="text-xs px-2.5 py-1 rounded-full font-medium bg-green-50 text-green-600">Selesai</span>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Kategori</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $surat->kategori->nama_kategori ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Diinput Oleh</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $surat->user->name ?? '-' }}</p>
                                        </div>
                                        @if($surat->keterangan)
                                        <div class="col-span-2">
                                            <p class="text-xs text-gray-400 mb-0.5">Keterangan</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $surat->keterangan }}</p>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Ringkasan Disposisi --}}
                                    @if($surat->disposisis->count() > 0)
                                    <div class="mt-5 pt-5 border-t dark:border-gray-700">
                                        <h5 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Ringkasan Disposisi</h5>
                                        <div class="space-y-2">
                                            @foreach($surat->disposisis->sortBy('created_at') as $d)
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 text-white font-bold
                                                    {{ $d->keputusan === 'ditolak' ? 'bg-red-500' : ($d->status === 'selesai' ? 'bg-green-500' : 'bg-blue-500') }}"
                                                    style="font-size:9px;">{{ $d->level }}</span>
                                                <span class="text-gray-600 dark:text-gray-300">{{ $d->dariUser->name ?? '?' }}</span>
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                                <span class="text-gray-600 dark:text-gray-300">{{ $d->kepadaUser->name ?? '?' }}</span>
                                                @if($d->keputusan === 'setuju')
                                                <span class="text-green-600">✅</span>
                                                @elseif($d->keputusan === 'ditolak')
                                                <span class="text-red-500">❌</span>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="px-6 py-4 border-t dark:border-gray-700 flex gap-2 flex-shrink-0">
                                    {{-- File --}}
                                    @if($surat->file_path)
                                    <a href="{{ Storage::url($surat->file_path) }}" target="_blank"
                                       class="inline-flex items-center gap-2 text-sm bg-green-100 text-green-700 hover:bg-green-200 px-4 py-2 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Lihat File
                                    </a>
                                    @endif

                                    {{-- Tracking --}}
                                    <a href="{{ route('disposisi.tracking', $surat) }}"
                                       class="inline-flex items-center gap-2 text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                        </svg>
                                        Tracking
                                    </a>

                                    {{-- Cetak --}}
                                    @if($lastDisposisi)
                                    <a href="{{ route('disposisi.cetak', $lastDisposisi) }}" target="_blank"
                                       class="inline-flex items-center gap-2 text-sm bg-purple-100 text-purple-700 hover:bg-purple-200 px-4 py-2 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                        Cetak Disposisi
                                    </a>
                                    @endif

                                    <button onclick="document.getElementById('arsip-modal-{{ $surat->id }}').classList.add('hidden')"
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
                    <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                        <p class="text-sm">Belum ada arsip surat masuk</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($suratMasuks->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
        {{ $suratMasuks->links() }}
    </div>
    @endif
</div>
@endif

{{-- ═══════════════════════════════════════ --}}
{{-- TAB SURAT KELUAR                        --}}
{{-- ═══════════════════════════════════════ --}}
@if($tab === 'keluar')
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
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($suratKeluars as $i => $surat)
                @php
                    $skSifatColor = match($surat->sifat) {
                        'penting' => 'bg-yellow-50 text-yellow-600',
                        'rahasia' => 'bg-purple-50 text-purple-600',
                        default   => 'bg-gray-100 text-gray-500',
                    };
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $suratKeluars->firstItem() + $i }}</td>
                    <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-300">{{ $surat->nomor_surat }}</td>
                    <td class="px-4 py-3 max-w-xs">
                        <div class="font-medium text-gray-800 dark:text-white truncate">{{ Str::limit($surat->judul_surat, 40) }}</div>
                        @if($surat->kategori)
                        <div class="text-xs text-gray-400 mt-0.5">{{ $surat->kategori->nama_kategori }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $surat->penerima }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">

                            {{-- Detail popup --}}
                            <button onclick="document.getElementById('arsip-sk-modal-{{ $surat->id }}').classList.remove('hidden')"
                                    title="Detail"
                                    class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>

                            {{-- File --}}
                            @if($surat->file_path)
                            <a href="{{ Storage::url($surat->file_path) }}" target="_blank" title="Lihat File"
                               class="p-1.5 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </a>
                            @endif

                            {{-- Edit — staff saja --}}
                            @if(in_array(auth()->user()->role, ['staff', 'admin']))
                            <a href="{{ route('surat-keluar.edit', $surat) }}" title="Edit"
                               class="p-1.5 text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @endif

                        </div>
                    </td>
                </tr>

                {{-- Modal Detail Surat Keluar --}}
                <tr>
                    <td colspan="6" class="p-0 border-0">
                        <div id="arsip-sk-modal-{{ $surat->id }}"
                             class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
                             onclick="if(event.target===this) this.classList.add('hidden')">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">

                                <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 flex-shrink-0">
                                    <h4 class="font-semibold text-gray-800 dark:text-white">Detail Surat Keluar</h4>
                                    <button onclick="document.getElementById('arsip-sk-modal-{{ $surat->id }}').classList.add('hidden')"
                                            class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 hover:text-gray-600 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

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
                                            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $skSifatColor }}">{{ ucfirst($surat->sifat) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Kategori</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $surat->kategori->nama_kategori ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Bagian</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $surat->bagian->nama_bagian ?? '-' }}</p>
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
                                    <button onclick="document.getElementById('arsip-sk-modal-{{ $surat->id }}').classList.add('hidden')"
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
                    <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <p class="text-sm">Belum ada surat keluar</p>
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
@endif

@endsection