@extends('layouts.app')
@section('title', 'Edit Kategori')

@section('content')
<div class="max-w-lg mx-auto pb-28">

    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
            <a href="{{ route('master.kategori.index') }}" class="hover:text-blue-600 transition">Kategori</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 dark:text-gray-300">Edit</span>
        </div>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Edit Kategori Surat</h2>
        <p class="text-sm text-gray-400 mt-0.5">Perbarui nama kategori surat</p>
    </div>

    <form id="main-form" action="{{ route('master.kategori.update', $kategori) }}" method="POST"
        data-konfirmasi
        data-tipe="edit"
        data-judul="Perbarui Kategori?"
        data-pesan="Nama kategori ini akan diperbarui."
        data-label-ok="Ya, Perbarui">
        @csrf @method('PUT')

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Informasi Kategori</h3>
                    <p class="text-xs text-gray-400">Nama kategori surat</p>
                </div>
            </div>
            <div class="p-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Nama Kategori <span class="text-red-500 normal-case">*</span></label>
                    <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                           class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('nama_kategori') border-red-400 @enderror">
                    @error('nama_kategori')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex justify-start">
            <a href="{{ route('master.kategori.index') }}"
               class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Batal
            </a>
        </div>
    </form>
</div>

<div class="fixed bottom-0 left-64 right-0 z-50">
    <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-2xl px-6 py-4">
        <div class="max-w-lg mx-auto flex items-center justify-between gap-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui data kategori</p>
            <button type="submit" form="main-form"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Perbarui Kategori
            </button>
        </div>
    </div>
</div>

@endsection