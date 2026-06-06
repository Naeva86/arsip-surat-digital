@extends('layouts.app')
@section('title', 'Tracking Surat Masuk')

@section('content')

<div class="max-w-3xl mx-auto pb-8">

    {{-- Breadcrumb --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
            <a href="{{ route('surat-masuk.index') }}" class="hover:text-blue-600 transition">Surat Masuk</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 dark:text-gray-300">Tracking</span>
        </div>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tracking Surat Masuk</h2>
        <p class="text-sm text-gray-400 mt-0.5">Riwayat alur disposisi surat</p>
    </div>

    {{-- Header Card — ringkasan surat + tombol detail popup --}}
    @php
        $stColor = match($suratMasuk->status) {
            'baru'              => 'bg-gray-100 text-gray-600',
            'menunggu_direktur' => 'bg-blue-50 text-blue-600',
            'ditolak'           => 'bg-red-50 text-red-600',
            'proses_disposisi'  => 'bg-yellow-50 text-yellow-600',
            'selesai'           => 'bg-green-50 text-green-600',
            default             => 'bg-gray-100 text-gray-500',
        };
    @endphp
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
        <div class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white truncate">{{ $suratMasuk->judul_surat }}</h3>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="font-mono text-xs text-gray-400">{{ $suratMasuk->no_agenda }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $stColor }}">{{ $suratMasuk->status_label }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0 ml-3">
                {{-- Tombol Detail Popup --}}
                <button onclick="document.getElementById('modal-detail-surat').classList.remove('hidden')"
                        class="inline-flex items-center gap-1.5 text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1.5 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Detail
                </button>

                @if($suratMasuk->file_path)
                <a href="{{ Storage::url($suratMasuk->file_path) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 text-xs font-medium bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1.5 rounded-lg transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    File
                </a>
                @endif

                @if(in_array($suratMasuk->status, ['baru', 'ditolak']) && in_array(auth()->user()->role, ['staff', 'admin']))
                <a href="{{ route('surat-masuk.edit', $suratMasuk) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-medium bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-3 py-1.5 rounded-lg transition">
                    Edit
                </a>
                @endif
            </div>
        </div>

        {{-- Catatan penolakan --}}
        @if($suratMasuk->status === 'ditolak')
        @php $penolakan = $suratMasuk->disposisis->where('keputusan', 'ditolak')->last(); @endphp
        @if($penolakan)
        <div class="mx-6 mb-4 flex items-start gap-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl px-4 py-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-red-700 dark:text-red-400">Ditolak oleh Direktur</p>
                @if($penolakan->catatan_penolakan)
                <p class="text-xs text-red-600 dark:text-red-500 mt-0.5">{{ $penolakan->catatan_penolakan }}</p>
                @endif
                <p class="text-xs text-red-400 mt-1">Edit dan simpan ulang untuk mengirim kembali ke Direktur.</p>
            </div>
        </div>
        @endif
        @endif
    </div>

    {{-- Cetak — hanya saat selesai --}}
    @if($suratMasuk->status === 'selesai')
    @php $lastDisposisi = $suratMasuk->disposisis->sortByDesc('created_at')->first(); @endphp
    @if($lastDisposisi)
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
        <div class="px-6 py-4 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Cetak Lembar Disposisi</h3>
                <p class="text-xs text-gray-400 mt-0.5">Disposisi surat ini telah selesai</p>
            </div>
            <a href="{{ route('disposisi.cetak', $lastDisposisi) }}" target="_blank"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak
            </a>
        </div>
    </div>
    @endif
    @endif

    {{-- Timeline Disposisi --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Alur Disposisi</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ $suratMasuk->disposisis->count() }} tahap</p>
        </div>
        <div class="p-6">
            @if($suratMasuk->disposisis->isEmpty())
            <div class="text-center py-8 text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm">Belum ada disposisi</p>
            </div>
            @else
            <div class="relative pl-8 space-y-0">
                <div class="absolute left-[15px] top-3 bottom-3 w-0.5 bg-gray-200 dark:bg-gray-700"></div>

                @foreach($suratMasuk->disposisis->sortBy('created_at') as $d)
                @php
                    $dotColor = match(true) {
                        $d->keputusan === 'ditolak' => 'bg-red-500 border-red-500',
                        $d->status === 'selesai'    => 'bg-green-500 border-green-500',
                        $d->status === 'menunggu'   => 'bg-blue-500 border-blue-500 animate-pulse',
                        $d->status === 'dibaca'     => 'bg-yellow-500 border-yellow-500',
                        default                     => 'bg-gray-400 border-gray-400',
                    };
                    $badgeColor = match($d->status) {
                        'menunggu' => 'bg-blue-50 text-blue-600',
                        'dibaca'   => 'bg-sky-50 text-sky-600',
                        'selesai'  => 'bg-green-50 text-green-600',
                        default    => 'bg-gray-100 text-gray-500',
                    };
                @endphp
                <div class="relative pb-6 last:pb-0">
                    <div class="absolute -left-8 top-1 w-4 h-4 rounded-full border-2 {{ $dotColor }} z-10"></div>

                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-600 p-4">

                        {{-- Header --}}
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-blue-600">{{ strtoupper(substr($d->dariUser->name ?? '?', 0, 1)) }}</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-800 dark:text-white">{{ $d->dariUser->name ?? '?' }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                                <div class="w-7 h-7 rounded-full bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-purple-600">{{ strtoupper(substr($d->kepadaUser->name ?? '?', 0, 1)) }}</span>
                                </div>
                                <span class="text-sm font-semibold text-gray-800 dark:text-white">{{ $d->kepadaUser->name ?? '?' }}</span>
                            </div>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $badgeColor }} flex-shrink-0">
                                {{ ucfirst($d->status) }}
                            </span>
                        </div>

                        {{-- Instruksi --}}
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">{{ $d->isi_disposisi }}</p>

                        {{-- Keputusan --}}
                        @if($d->keputusan === 'ditolak')
                        <div class="flex items-start gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg px-3 py-2 mb-2">
                            <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <div>
                                <p class="text-xs font-semibold text-red-700 dark:text-red-400">Ditolak</p>
                                @if($d->catatan_penolakan)
                                <p class="text-xs text-red-600 dark:text-red-500 mt-0.5">{{ $d->catatan_penolakan }}</p>
                                @endif
                            </div>
                        </div>
                        @elseif($d->keputusan === 'setuju')
                        <div class="flex items-center gap-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg px-3 py-2 mb-2">
                            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p class="text-xs font-semibold text-green-700 dark:text-green-400">Disetujui</p>
                            @if($d->instruksi_disposisi)
                            <span class="text-xs text-green-600"> — {{ $d->instruksi_disposisi }}</span>
                            @endif
                        </div>
                        @endif

                        {{-- Timestamps --}}
                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400 mt-2">
                            <span>Dikirim: {{ $d->created_at->format('d/m/Y H:i') }}</span>
                            @if($d->dibaca_at)
                            <span>Dibaca: {{ $d->dibaca_at->format('d/m/Y H:i') }}</span>
                            @endif
                            @if($d->selesai_at)
                            <span>Selesai: {{ $d->selesai_at->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="mt-5">
        <a href="{{ route('surat-masuk.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
    </div>

</div>

{{-- ═══════════════════════════════════════════ --}}
{{-- MODAL DETAIL SURAT (POPUP)                  --}}
{{-- ═══════════════════════════════════════════ --}}
@php
    $sfC = match($suratMasuk->sifat) {
        'urgent'  => 'bg-red-50 text-red-600',
        'penting' => 'bg-yellow-50 text-yellow-600',
        'rahasia' => 'bg-purple-50 text-purple-600',
        default   => 'bg-gray-100 text-gray-500',
    };
@endphp
<div id="modal-detail-surat"
     class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
     onclick="if(event.target===this) this.classList.add('hidden')">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 flex-shrink-0">
            <div>
                <h4 class="font-semibold text-gray-800 dark:text-white">Detail Surat Masuk</h4>
                <p class="text-xs text-gray-400 mt-0.5">No Agenda: {{ $suratMasuk->no_agenda }}</p>
            </div>
            <button onclick="document.getElementById('modal-detail-surat').classList.add('hidden')"
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
                    <p class="font-medium text-gray-800 dark:text-white">{{ $suratMasuk->nomor_surat }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Pengirim</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $suratMasuk->pengirim }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-400 mb-0.5">Perihal</p>
                    <p class="font-medium text-gray-800 dark:text-white">{{ $suratMasuk->judul_surat }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Tanggal Surat</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $suratMasuk->tanggal_surat->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Tanggal Arsip</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $suratMasuk->tanggal_arsip->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Sifat</p>
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $sfC }}">{{ ucfirst($suratMasuk->sifat) }}</span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Status</p>
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $stColor }}">{{ $suratMasuk->status_label }}</span>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Kategori</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $suratMasuk->kategori->nama_kategori ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Diinput Oleh</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $suratMasuk->user->name ?? '-' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-400 mb-0.5">Keterangan</p>
                    <p class="text-gray-700 dark:text-gray-300">{{ $suratMasuk->keterangan }}</p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t dark:border-gray-700 flex gap-2 flex-shrink-0">
            @if($suratMasuk->file_path)
            <a href="{{ Storage::url($suratMasuk->file_path) }}" target="_blank"
               class="inline-flex items-center gap-2 text-sm bg-green-100 text-green-700 hover:bg-green-200 px-4 py-2 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Lihat File
            </a>
            @endif
            <button onclick="document.getElementById('modal-detail-surat').classList.add('hidden')"
                    class="text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white text-gray-700 px-4 py-2 rounded-lg transition ml-auto">
                Tutup
            </button>
        </div>

    </div>
</div>

@endsection