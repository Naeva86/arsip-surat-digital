@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

@php $role = auth()->user()->role; @endphp

{{-- Stats Cards — Semua role --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Surat Masuk</p>
                <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalSuratMasuk }}</p>
            </div>
            <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-xl">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-3">Bulan ini: <span class="font-semibold text-gray-600 dark:text-gray-300">{{ $suratMasukBulanIni }}</span></p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Surat Keluar</p>
                <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalSuratKeluar }}</p>
            </div>
            <div class="bg-green-100 dark:bg-green-900 p-3 rounded-xl">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-3">Bulan ini: <span class="font-semibold text-gray-600 dark:text-gray-300">{{ $suratKeluarBulanIni }}</span></p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Proses Disposisi</p>
                <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $prosesDisposisi }}</p>
            </div>
            <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-xl">
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-3">Menunggu tindak lanjut</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Disposisi Masuk</p>
                <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $disposisiSaya }}</p>
            </div>
            <div class="bg-red-100 dark:bg-red-900 p-3 rounded-xl">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-3">Belum ditindaklanjuti</p>
    </div>

</div>

{{-- ═══════════════════════════════════════════════ --}}
{{-- ADMIN & STAFF: Charts + Tabel Lama              --}}
{{-- ═══════════════════════════════════════════════ --}}
@if(in_array($role, ['admin', 'staff']))

{{-- Charts --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-6">

    {{-- Line Chart --}}
    <div class="xl:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="font-semibold text-gray-700 dark:text-white mb-4">Tren Surat 6 Bulan Terakhir</h3>
        <div style="height: 180px;">
            <canvas id="chartTren"></canvas>
        </div>
    </div>

    {{-- Donut Chart --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="font-semibold text-gray-700 dark:text-white mb-4">Status Surat Masuk</h3>
        <div style="height: 140px;">
            <canvas id="chartStatus"></canvas>
        </div>
        <div class="mt-4 space-y-2">
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">Menunggu Direktur</span>
                </div>
                <span class="font-semibold dark:text-white">{{ $statusData[0] }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">Proses Disposisi</span>
                </div>
                <span class="font-semibold dark:text-white">{{ $statusData[1] }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">Ditolak</span>
                </div>
                <span class="font-semibold dark:text-white">{{ $statusData[2] }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">Selesai</span>
                </div>
                <span class="font-semibold dark:text-white">{{ $statusData[3] }}</span>
            </div>
        </div>
    </div>

</div>

{{-- Tabel Surat Masuk Terbaru --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b dark:border-gray-700 flex items-center justify-between">
        <h3 class="font-semibold text-gray-700 dark:text-white">Surat Masuk Terbaru</h3>
        <a href="{{ route('surat-masuk.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">No Agenda</th>
                    <th class="px-6 py-3 text-left">Perihal</th>
                    <th class="px-6 py-3 text-left">Pengirim</th>
                    <th class="px-6 py-3 text-left">Tanggal</th>
                    <th class="px-6 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($suratMasukTerbaru as $surat)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-3 font-mono text-xs text-gray-600 dark:text-gray-400">{{ $surat->no_agenda }}</td>
                    <td class="px-6 py-3 dark:text-white">{{ Str::limit($surat->judul_surat, 40) }}</td>
                    <td class="px-6 py-3 text-gray-500 dark:text-gray-400">{{ $surat->pengirim }}</td>
                    <td class="px-6 py-3 text-gray-500 dark:text-gray-400">{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                    <td class="px-6 py-3">
                        @php
                            $color = match($surat->status) {
                                'menunggu_direktur' => 'bg-blue-100 text-blue-700',
                                'proses_disposisi'  => 'bg-yellow-100 text-yellow-700',
                                'ditolak'           => 'bg-red-100 text-red-700',
                                'selesai'           => 'bg-green-100 text-green-700',
                                default             => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $color }}">
                            {{ $surat->status_label ?? ucfirst(str_replace('_', ' ', $surat->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada surat masuk</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const isDark     = document.documentElement.classList.contains('dark');
    const gridColor  = isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)';
    const labelColor = isDark ? '#9ca3af' : '#6b7280';

    new Chart(document.getElementById('chartTren').getContext('2d'), {
        type: 'line',
        data: {
            labels: @json($bulanList),
            datasets: [
                {
                    label: 'Surat Masuk',
                    data: @json($dataMasuk),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59,130,246,0.1)',
                    borderWidth: 2, fill: true, tension: 0.4,
                    pointBackgroundColor: '#3B82F6', pointRadius: 3,
                },
                {
                    label: 'Surat Keluar',
                    data: @json($dataKeluar),
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16,185,129,0.1)',
                    borderWidth: 2, fill: true, tension: 0.4,
                    pointBackgroundColor: '#10B981', pointRadius: 3,
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { labels: { color: labelColor, boxWidth: 12, font: { size: 11 } } } },
            scales: {
                x: { grid: { color: gridColor }, ticks: { color: labelColor, font: { size: 10 } } },
                y: { grid: { color: gridColor }, ticks: { color: labelColor, stepSize: 1, font: { size: 10 } }, beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('chartStatus').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Menunggu Direktur', 'Proses Disposisi', 'Ditolak', 'Selesai'],
            datasets: [{
                data: @json($statusData),
                backgroundColor: ['#3B82F6', '#F59E0B', '#EF4444', '#10B981'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });
</script>

@endif

{{-- ═══════════════════════════════════════════════ --}}
{{-- DIREKTUR, KABAG, KASUBAG: Disposisi + Prioritas --}}
{{-- ═══════════════════════════════════════════════ --}}
@if(in_array($role, ['direktur', 'kabag', 'kasubbag']))

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Widget: Disposisi Menunggu --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Disposisi Menunggu</h3>
                <p class="text-xs text-gray-400">Surat yang perlu Anda proses</p>
            </div>
            @if($disposisiSaya > 0)
            <span class="ml-auto bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $disposisiSaya }}</span>
            @endif
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($disposisiMenunggu as $dm)
            @php
                $dmSifatDot = match($dm->suratMasuk->sifat ?? '') {
                    'urgent'  => 'bg-red-500 animate-pulse',
                    'penting' => 'bg-yellow-500',
                    'rahasia' => 'bg-purple-500',
                    default   => 'bg-blue-500',
                };
                $dmSifatBadge = match($dm->suratMasuk->sifat ?? '') {
                    'urgent'  => 'bg-red-50 text-red-600',
                    'penting' => 'bg-yellow-50 text-yellow-600',
                    'rahasia' => 'bg-purple-50 text-purple-600',
                    default   => 'bg-gray-100 text-gray-500',
                };
            @endphp
            <a href="{{ route('disposisi.show', $dm) }}"
               class="flex items-start gap-3 px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                <div class="w-2 h-2 rounded-full {{ $dmSifatDot }} mt-2 flex-shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 dark:text-white truncate">{{ Str::limit($dm->suratMasuk->judul_surat ?? '-', 35) }}</p>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-xs text-gray-400">Dari: {{ $dm->dariUser->name ?? '-' }}</span>
                        <span class="text-xs text-gray-300">•</span>
                        <span class="text-xs text-gray-400">{{ $dm->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $dmSifatBadge }} flex-shrink-0">{{ ucfirst($dm->suratMasuk->sifat ?? '-') }}</span>
            </a>
            @empty
            <div class="px-6 py-10 text-center text-gray-400">
                <svg class="w-8 h-8 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm">Tidak ada disposisi menunggu</p>
            </div>
            @endforelse
        </div>
        @if($disposisiSaya > 0)
        <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
            <a href="{{ route('disposisi.index') }}" class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium flex items-center gap-1">
                Buka Kotak Disposisi
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        @endif
    </div>

    {{-- Widget: Surat Prioritas --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/40 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Surat Prioritas</h3>
                <p class="text-xs text-gray-400">Belum selesai disposisi</p>
            </div>
        </div>

        {{-- Counter badges --}}
        <div class="px-6 pt-4 pb-2 flex items-center gap-2 flex-wrap">
            @if($countUrgent > 0)
            <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                {{ $countUrgent }} Urgent
            </span>
            @endif
            @if($countPenting > 0)
            <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-yellow-50 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                {{ $countPenting }} Penting
            </span>
            @endif
            @if($countRahasia > 0)
            <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                {{ $countRahasia }} Rahasia
            </span>
            @endif
            @if($countUrgent == 0 && $countPenting == 0 && $countRahasia == 0)
            <span class="text-xs text-green-500 font-medium">✓ Semua surat prioritas sudah selesai</span>
            @endif
        </div>

        {{-- List --}}
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($suratPentings as $sp)
            @php
                $spDot = match($sp->sifat) {
                    'urgent'  => 'bg-red-500 animate-pulse',
                    'penting' => 'bg-yellow-500',
                    'rahasia' => 'bg-purple-500',
                    default   => 'bg-gray-400',
                };
                $spStatus = match($sp->status) {
                    'menunggu_direktur' => 'bg-blue-50 text-blue-600',
                    'proses_disposisi'  => 'bg-yellow-50 text-yellow-600',
                    'ditolak'           => 'bg-red-50 text-red-600',
                    default             => 'bg-gray-100 text-gray-500',
                };
                $spStatusLabel = match($sp->status) {
                    'menunggu_direktur' => 'Menunggu',
                    'proses_disposisi'  => 'Proses',
                    'ditolak'           => 'Ditolak',
                    default             => ucfirst($sp->status),
                };
            @endphp
            <div class="flex items-start gap-3 px-6 py-3.5">
                <div class="w-2 h-2 rounded-full {{ $spDot }} mt-2 flex-shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-medium text-gray-800 dark:text-white truncate">{{ Str::limit($sp->judul_surat, 30) }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $spStatus }} flex-shrink-0">{{ $spStatusLabel }}</span>
                    </div>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-xs text-gray-400">{{ $sp->pengirim }}</span>
                        <span class="text-xs text-gray-300">•</span>
                        <span class="text-xs text-gray-400">{{ $sp->tanggal_surat->format('d/m/Y') }}</span>
                        <span class="text-xs text-gray-300">•</span>
                        <span class="text-xs font-medium {{ $sp->sifat === 'urgent' ? 'text-red-500' : ($sp->sifat === 'penting' ? 'text-yellow-600' : 'text-purple-500') }}">{{ ucfirst($sp->sifat) }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-10 text-center text-gray-400">
                <svg class="w-8 h-8 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm">Tidak ada surat prioritas aktif</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

@endif

@endsection