@extends('layouts.app')
@section('title', 'Detail Surat Keluar')
@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-semibold text-gray-700">Detail Surat Keluar</h3>
            <div class="flex gap-2">
                @if($suratKeluar->file_path)
                <a href="{{ Storage::url($suratKeluar->file_path) }}" target="_blank"
                   class="text-sm bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1.5 rounded-lg">📄 Lihat File</a>
                @endif
                <a href="{{ route('surat-keluar.edit', $suratKeluar) }}"
                   class="text-sm bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-3 py-1.5 rounded-lg">Edit</a>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Nomor Surat</p>
                <p class="font-medium">{{ $suratKeluar->nomor_surat }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Penerima</p>
                <p>{{ $suratKeluar->penerima }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-gray-400 text-xs mb-0.5">Perihal</p>
                <p class="font-medium">{{ $suratKeluar->judul_surat }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Tanggal Surat</p>
                <p>{{ $suratKeluar->tanggal_surat->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Tanggal Arsip</p>
                <p>{{ $suratKeluar->tanggal_arsip->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Sifat</p>
                <span class="text-xs px-2 py-1 rounded-full font-medium bg-gray-100 text-gray-600">{{ ucfirst($suratKeluar->sifat) }}</span>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Kategori</p>
                <p>{{ $suratKeluar->kategori->nama_kategori ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Bagian</p>
                <p>{{ $suratKeluar->bagian->nama_bagian ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Dibuat Oleh</p>
                <p>{{ $suratKeluar->user->name }}</p>
            </div>
            @if($suratKeluar->keterangan)
            <div class="col-span-2">
                <p class="text-gray-400 text-xs mb-0.5">Keterangan</p>
                <p>{{ $suratKeluar->keterangan }}</p>
            </div>
            @endif
        </div>
        <div class="mt-5">
            <a href="{{ route('surat-keluar.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali</a>
        </div>
    </div>
</div>
@endsection