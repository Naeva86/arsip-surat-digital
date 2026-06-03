@extends('layouts.app')
@section('title', 'Laporan Arsip Surat')

@section('content')

{{-- Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-5">
    <form method="GET" action="{{ route('laporan.index') }}"
          class="flex flex-wrap gap-3 items-end">

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Bulan</label>
            <select name="bulan"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach(range(1, 12) as $b)
                <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                    {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
            <select name="tahun"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach(range(now()->year, now()->year - 4) as $t)
                <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg">
            Tampilkan
        </button>

        {{-- Export buttons --}}
        <div class="ml-auto flex gap-2">
            <a href="{{ route('laporan.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
               class="bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export PDF
            </a>
            <a href="{{ route('laporan.excel', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
               class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Excel
            </a>
        </div>

    </form>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-3xl font-bold text-blue-600">{{ $suratMasuks->count() }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Surat Masuk</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-3xl font-bold text-green-600">{{ $suratKeluars->count() }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Surat Keluar</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-3xl font-bold text-yellow-600">
            {{ $suratMasuks->where('status', 'proses_disposisi')->count() }}
        </p>
        <p class="text-xs text-gray-500 mt-1">Proses Disposisi</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-3xl font-bold text-purple-600">
            {{ $suratMasuks->where('status', 'selesai')->count() }}
        </p>
        <p class="text-xs text-gray-500 mt-1">Surat Selesai</p>
    </div>
</div>

{{-- Tabel Surat Masuk --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-5">
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <h3 class="font-semibold text-gray-700">
            Surat Masuk —
            {{ DateTime::createFromFormat('!m', $bulan)->format('F') }}
            {{ $tahun }}
        </h3>
        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
            {{ $suratMasuks->count() }} surat
        </span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">No Agenda</th>
                    <th class="px-4 py-3 text-left">Nomor Surat</th>
                    <th class="px-4 py-3 text-left">Perihal</th>
                    <th class="px-4 py-3 text-left">Pengirim</th>
                    <th class="px-4 py-3 text-left">Tgl Surat</th>
                    <th class="px-4 py-3 text-left">Sifat</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($suratMasuks as $i => $surat)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 font-mono text-xs">{{ $surat->no_agenda }}</td>
                    <td class="px-4 py-3 text-xs">{{ $surat->nomor_surat }}</td>
                    <td class="px-4 py-3">{{ Str::limit($surat->judul_surat, 35) }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $surat->pengirim }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        {{ $surat->tanggal_surat->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $sifatColor = match($surat->sifat) {
                                'urgent'  => 'bg-red-100 text-red-700',
                                'penting' => 'bg-yellow-100 text-yellow-700',
                                'rahasia' => 'bg-purple-100 text-purple-700',
                                default   => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full font-medium {{ $sifatColor }}">
                            {{ ucfirst($surat->sifat) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $statusColor = match($surat->status) {
                                'baru'             => 'bg-blue-100 text-blue-700',
                                'proses_disposisi' => 'bg-yellow-100 text-yellow-700',
                                'selesai'          => 'bg-green-100 text-green-700',
                                default            => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full font-medium {{ $statusColor }}">
                            {{ $surat->status_label }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                        Tidak ada surat masuk pada periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Tabel Surat Keluar --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <h3 class="font-semibold text-gray-700">
            Surat Keluar —
            {{ DateTime::createFromFormat('!m', $bulan)->format('F') }}
            {{ $tahun }}
        </h3>
        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
            {{ $suratKeluars->count() }} surat
        </span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nomor Surat</th>
                    <th class="px-4 py-3 text-left">Perihal</th>
                    <th class="px-4 py-3 text-left">Penerima</th>
                    <th class="px-4 py-3 text-left">Tgl Surat</th>
                    <th class="px-4 py-3 text-left">Bagian</th>
                    <th class="px-4 py-3 text-left">Sifat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($suratKeluars as $i => $surat)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 text-xs">{{ $surat->nomor_surat }}</td>
                    <td class="px-4 py-3">{{ Str::limit($surat->judul_surat, 35) }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $surat->penerima }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        {{ $surat->tanggal_surat->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $surat->bagian->nama_bagian ?? '-' }}
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $sifatColor = match($surat->sifat) {
                                'penting' => 'bg-yellow-100 text-yellow-700',
                                'rahasia' => 'bg-purple-100 text-purple-700',
                                default   => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full font-medium {{ $sifatColor }}">
                            {{ ucfirst($surat->sifat) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                        Tidak ada surat keluar pada periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection