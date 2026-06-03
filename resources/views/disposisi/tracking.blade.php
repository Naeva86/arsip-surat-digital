@extends('layouts.app')
@section('title', 'Tracking Disposisi')

@section('content')

<div class="max-w-3xl mx-auto pb-8">

    {{-- Breadcrumb --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
            <a href="{{ url()->previous() }}" class="hover:text-blue-600 transition">← Kembali</a>
        </div>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tracking Disposisi</h2>
        <p class="text-sm text-gray-400 mt-0.5">Riwayat alur disposisi surat</p>
    </div>

    {{-- Info Surat --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">{{ $suratMasuk->judul_surat }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $suratMasuk->no_agenda }} — {{ $suratMasuk->nomor_surat }}</p>
            </div>
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
            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $stColor }}">{{ $suratMasuk->status_label }}</span>
        </div>
        <div class="p-6 grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div>
                <p class="text-xs text-gray-400">Pengirim</p>
                <p class="text-gray-700 dark:text-gray-300">{{ $suratMasuk->pengirim }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Tanggal Surat</p>
                <p class="text-gray-700 dark:text-gray-300">{{ $suratMasuk->tanggal_surat->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Sifat</p>
                @php
                    $sfColor = match($suratMasuk->sifat) {
                        'urgent'  => 'bg-red-50 text-red-600',
                        'penting' => 'bg-yellow-50 text-yellow-600',
                        'rahasia' => 'bg-purple-50 text-purple-600',
                        default   => 'bg-gray-100 text-gray-500',
                    };
                @endphp
                <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $sfColor }}">{{ ucfirst($suratMasuk->sifat) }}</span>
            </div>
            <div>
                <p class="text-xs text-gray-400">Diinput Oleh</p>
                <p class="text-gray-700 dark:text-gray-300">{{ $suratMasuk->user->name ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Alur Disposisi</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ $suratMasuk->disposisis->count() }} tahap disposisi</p>
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
                {{-- Garis vertikal --}}
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
                    {{-- Dot --}}
                    <div class="absolute -left-8 top-1 w-4 h-4 rounded-full border-2 {{ $dotColor }} z-10"></div>

                    {{-- Card --}}
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

                        {{-- Logs --}}
                        @if($d->logs->count() > 0)
                        <details class="mt-3">
                            <summary class="text-xs text-blue-600 cursor-pointer hover:underline">
                                Lihat {{ $d->logs->count() }} log aktivitas
                            </summary>
                            <div class="mt-2 space-y-1.5 pl-3 border-l-2 border-gray-200 dark:border-gray-600">
                                @foreach($d->logs->sortByDesc('created_at') as $log)
                                <div class="text-xs">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $log->user->name ?? '?' }}</span>
                                    <span class="text-gray-400">— {{ $log->aksi }}</span>
                                    <span class="text-gray-400 block">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @endforeach
                            </div>
                        </details>
                        @endif

                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

</div>

@endsection