@extends('layouts.app')
@section('title', 'Edit Jabatan')

@section('content')
<div class="max-w-lg mx-auto pb-28">

    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
            <a href="{{ route('master.jabatan.index') }}" class="hover:text-blue-600 transition">Jabatan</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 dark:text-gray-300">Edit</span>
        </div>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Edit Jabatan</h2>
    </div>

    <form id="main-form" action="{{ route('master.jabatan.update', $jabatan) }}" method="POST"
          data-konfirmasi data-tipe="edit" data-judul="Perbarui Jabatan?" data-pesan="Data jabatan akan diperbarui." data-label-ok="Ya, Perbarui">
        @csrf @method('PUT')

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Informasi Jabatan</h3>
                    <p class="text-xs text-gray-400">Perbarui nama dan level</p>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Nama Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_jabatan" value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}"
                           class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('nama_jabatan') border-red-400 @enderror">
                    @error('nama_jabatan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-2 uppercase tracking-wider">Level <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([1 => 'Direktur', 2 => 'Kepala Bagian', 3 => 'Kepala Sub Bagian', 4 => 'Staff'] as $lvl => $nama)
                        @php $isActive = (int) old('level', $jabatan->level) === $lvl; @endphp
                        <label class="level-label flex items-center gap-2.5 px-3 py-3 border rounded-xl cursor-pointer transition
                            {{ $isActive ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <input type="radio" name="level" value="{{ $lvl }}" {{ $isActive ? 'checked' : '' }} class="hidden level-radio">
                            <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0
                                {{ $isActive ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">{{ $lvl }}</span>
                            <span class="text-sm {{ $isActive ? 'text-blue-600 font-medium' : 'text-gray-600 dark:text-gray-300' }}">Level {{ $lvl }} — {{ $nama }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('level')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <a href="{{ route('master.jabatan.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Batal
        </a>
    </form>
</div>

<div class="fixed bottom-0 left-64 right-0 z-50">
    <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-2xl px-6 py-4">
        <div class="max-w-lg mx-auto flex items-center justify-between gap-4">
            <p class="text-sm text-gray-500">Perbarui data jabatan</p>
            <button type="submit" form="main-form" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Perbarui
            </button>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.level-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.level-label').forEach(function(lbl) {
            var num = lbl.querySelector('span:first-child');
            var txt = lbl.querySelector('span:last-child');
            lbl.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            lbl.classList.add('border-gray-200', 'dark:border-gray-600');
            if (num) { num.classList.remove('bg-blue-600', 'text-white'); num.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-500'); }
            if (txt) { txt.classList.remove('text-blue-600', 'font-medium'); txt.classList.add('text-gray-600', 'dark:text-gray-300'); }
        });
        var lbl = this.closest('.level-label');
        var num = lbl.querySelector('span:first-child');
        var txt = lbl.querySelector('span:last-child');
        lbl.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        lbl.classList.remove('border-gray-200', 'dark:border-gray-600');
        if (num) { num.classList.add('bg-blue-600', 'text-white'); num.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-500'); }
        if (txt) { txt.classList.add('text-blue-600', 'font-medium'); txt.classList.remove('text-gray-600', 'dark:text-gray-300'); }
    });
});
</script>
@endsection