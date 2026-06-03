@extends('layouts.app')
@section('title', 'Edit Bagian')

@section('content')
<div class="max-w-lg mx-auto pb-28">

    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
            <a href="{{ route('master.bagian.index') }}" class="hover:text-blue-600 transition">Bagian</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 dark:text-gray-300">Edit</span>
        </div>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Edit Bagian</h2>
    </div>

    <form id="main-form" action="{{ route('master.bagian.update', $bagian) }}" method="POST"
          data-konfirmasi data-tipe="edit" data-judul="Perbarui Bagian?" data-pesan="Data bagian akan diperbarui." data-label-ok="Ya, Perbarui">
        @csrf @method('PUT')

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Informasi Bagian</h3>
                    <p class="text-xs text-gray-400">Perbarui nama dan induk bagian</p>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Nama Bagian <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_bagian" value="{{ old('nama_bagian', $bagian->nama_bagian) }}"
                           class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('nama_bagian') border-red-400 @enderror">
                    @error('nama_bagian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Bagian Induk</label>
                    <select name="parent_id" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">— Tidak Ada (Bagian Utama) —</option>
                        @foreach($parents as $p)
                        <option value="{{ $p->id }}" {{ old('parent_id', $bagian->parent_id) == $p->id ? 'selected' : '' }}>{{ $p->nama_bagian }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <a href="{{ route('master.bagian.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Batal
        </a>
    </form>
</div>

<div class="fixed bottom-0 left-64 right-0 z-50">
    <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-2xl px-6 py-4">
        <div class="max-w-lg mx-auto flex items-center justify-between gap-4">
            <p class="text-sm text-gray-500">Perbarui data bagian</p>
            <button type="submit" form="main-form" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Perbarui
            </button>
        </div>
    </div>
</div>
@endsection