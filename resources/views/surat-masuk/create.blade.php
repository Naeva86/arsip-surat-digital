@extends('layouts.app')
@section('title', 'Tambah Surat Masuk')

@section('content')

@php
    // Ambil daftar nama file yang sudah ada di storage (pakai @php block, bukan @json inline)
    $existingFileNames = \App\Models\SuratMasuk::whereNotNull('file_path')
        ->pluck('file_path')
        ->map(function($path) { return basename($path); })
        ->values()
        ->toArray();
@endphp

<div class="max-w-3xl mx-auto pb-28">

    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
            <a href="{{ route('surat-masuk.index') }}" class="hover:text-blue-600 transition">Surat Masuk</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-600 dark:text-gray-300">Tambah Baru</span>
        </div>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tambah Surat Masuk</h2>
        <p class="text-sm text-gray-400 mt-0.5">Isi semua kolom yang diperlukan dengan benar</p>
    </div>

    <form id="form-surat" action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data"
        data-konfirmasi
        data-tipe="simpan"
        data-judul="Simpan Surat Masuk?"
        data-pesan="Pastikan semua data sudah benar sebelum disimpan."
        data-label-ok="Ya, Simpan">
        @csrf

        {{-- Section 1: Identitas Surat --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Identitas Surat</h3>
                    <p class="text-xs text-gray-400">Nomor, perihal, dan pengirim surat</p>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- No Agenda --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">No Agenda</label>
                    <div class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2.5">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                        </svg>
                        <span class="text-sm font-mono text-gray-500 dark:text-gray-400">{{ $noAgenda }}</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Digenerate otomatis oleh sistem</p>
                </div>

                {{-- Nomor Surat --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                        Nomor Surat <span class="text-red-500 normal-case">*</span>
                    </label>
                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}"
                           placeholder="cth: 001/DISDIK/V/2025"
                           class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('nomor_surat') border-red-400 focus:ring-red-400 @enderror">
                    @error('nomor_surat')
                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Perihal --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                        Perihal <span class="text-red-500 normal-case">*</span>
                    </label>
                    <input type="text" name="judul_surat" value="{{ old('judul_surat') }}"
                           placeholder="Tuliskan perihal surat..."
                           class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('judul_surat') border-red-400 focus:ring-red-400 @enderror">
                    @error('judul_surat')
                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Pengirim --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                        Pengirim <span class="text-red-500 normal-case">*</span>
                    </label>
                    <input type="text" name="pengirim" value="{{ old('pengirim') }}"
                           placeholder="Nama instansi atau perorangan pengirim..."
                           class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('pengirim') border-red-400 focus:ring-red-400 @enderror">
                    @error('pengirim')
                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Section 2: Tanggal & Klasifikasi --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Tanggal & Klasifikasi</h3>
                    <p class="text-xs text-gray-400">Tanggal, sifat, dan kategori surat</p>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Tanggal Surat --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                        Tanggal Surat <span class="text-red-500 normal-case">*</span>
                    </label>
                    <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}"
                           class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('tanggal_surat') border-red-400 @enderror">
                    @error('tanggal_surat')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Arsip --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                        Tanggal Arsip <span class="text-red-500 normal-case">*</span>
                    </label>
                    <input type="date" name="tanggal_arsip" value="{{ old('tanggal_arsip', date('Y-m-d')) }}"
                           class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('tanggal_arsip') border-red-400 @enderror">
                    @error('tanggal_arsip')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sifat Surat --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                        Sifat Surat <span class="text-red-500 normal-case">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(['biasa' => ['label' => 'Biasa', 'color' => 'gray'], 'penting' => ['label' => 'Penting', 'color' => 'yellow'], 'rahasia' => ['label' => 'Rahasia', 'color' => 'purple'], 'urgent' => ['label' => 'Urgent', 'color' => 'red']] as $val => $opt)
                        <label class="sifat-label relative flex items-center gap-2 px-3 py-2 border rounded-lg cursor-pointer transition
                            {{ old('sifat', 'biasa') === $val ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <input type="radio" name="sifat" value="{{ $val }}"
                                   {{ old('sifat', 'biasa') === $val ? 'checked' : '' }}
                                   class="hidden sifat-radio">
                            <span class="w-3 h-3 rounded-full flex-shrink-0
                                {{ $opt['color'] === 'gray'   ? 'bg-gray-400' : '' }}
                                {{ $opt['color'] === 'yellow' ? 'bg-yellow-400' : '' }}
                                {{ $opt['color'] === 'purple' ? 'bg-purple-500' : '' }}
                                {{ $opt['color'] === 'red'    ? 'bg-red-500' : '' }}"></span>
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ $opt['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Kategori — sekarang --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                        Kategori <span class="text-red-500 normal-case">*</span>
                    </label>
                    <select name="kategori_id" id="kategori_id"
                            class="required-select w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Diagendakan Nomor --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Diagendakan Nomor</label>
                    <input type="text" name="diagendakan_nomor" value="{{ old('diagendakan_nomor') }}"
                           placeholder="Opsional"
                           class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Keterangan</label>
                    <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                           placeholder="Opsional"
                           class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

            </div>
        </div>

        {{-- Section 3: Upload File --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">File Surat</h3>
                    <p class="text-xs text-gray-400">Upload scan atau file digital surat</p>
                </div>
            </div>

            <div class="p-6">
                <label id="upload-area"
                       class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/30 transition group">
                    <div id="upload-placeholder" class="flex flex-col items-center gap-2 text-center">
                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600 group-hover:text-blue-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 group-hover:text-blue-500 transition">Klik untuk upload atau drag & drop</p>
                            <p class="text-xs text-gray-400 mt-0.5">PDF • Maks. 10MB</p>
                        </div>
                    </div>
                    <div id="upload-preview" class="hidden flex-col items-center gap-2">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p id="upload-filename" class="text-sm font-medium text-green-600 dark:text-green-400"></p>
                        <p class="text-xs text-gray-400">Klik untuk ganti file</p>
                    </div>
                    <input type="file" name="file_path" accept=".pdf" class="hidden" id="file-input">
                </label>

                {{-- Peringatan duplikat --}}
                <div id="duplicate-warning" class="hidden mt-3 flex items-start gap-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl px-4 py-3">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-amber-700 dark:text-amber-400">File sudah tersedia</p>
                        <p class="text-xs text-amber-600 dark:text-amber-500 mt-0.5">
                            File "<span id="duplicate-filename" class="font-medium"></span>" sudah ada di database. Silahkan masukkan surat yang lain.
                        </p>
                    </div>
                </div>

                @error('file_path')
                <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>
        </div>

        {{-- Tombol Batal (inline) --}}
        <div class="flex justify-start">
            <a href="{{ route('surat-masuk.index') }}"
               class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Batal
            </a>
        </div>
    </form>
</div>

{{-- Sticky Submit Bar --}}
<div id="sticky-bar" class="fixed bottom-0 left-64 right-0 z-50 translate-y-full transition-transform duration-300 ease-in-out">
    <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-2xl px-6 py-4">
        <div class="max-w-lg mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">Semua data wajib sudah terisi</span>
            </div>
            <button type="submit" form="form-surat"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Surat Masuk
            </button>
        </div>
    </div>
</div>

<script>
    var existingFileNames = @json($existingFileNames);

    var fileInput   = document.getElementById('file-input');
    var placeholder = document.getElementById('upload-placeholder');
    var preview     = document.getElementById('upload-preview');
    var filenameEl  = document.getElementById('upload-filename');
    var dupWarning  = document.getElementById('duplicate-warning');
    var dupFilename = document.getElementById('duplicate-filename');
    var stickyBar   = document.getElementById('sticky-bar');
    var isDuplicate = false;
    var hasFile     = false;

    function checkReady() {
        var ready = true;

        document.querySelectorAll('.required-field').forEach(function(f) {
            if (!f.value.trim()) ready = false;
        });

        document.querySelectorAll('.required-select').forEach(function(s) {
            if (!s.value) ready = false;
        });

        // File wajib di form create
        if (!hasFile) ready = false;

        if (isDuplicate) ready = false;

        if (ready) {
            stickyBar.classList.remove('translate-y-full');
        } else {
            stickyBar.classList.add('translate-y-full');
        }
    }

    fileInput.addEventListener('change', function() {
        var file = this.files[0];
        if (!file) {
            hasFile = false;
            checkReady();
            return;
        }

        var dup = existingFileNames.indexOf(file.name) !== -1;

        if (dup) {
            isDuplicate = true;
            hasFile     = false;
            dupFilename.textContent = file.name;
            dupWarning.classList.remove('hidden');
            fileInput.value = '';
            placeholder.classList.remove('hidden');
            preview.classList.add('hidden');
            preview.classList.remove('flex');
        } else {
            isDuplicate = false;
            hasFile     = true;
            dupWarning.classList.add('hidden');
            placeholder.classList.add('hidden');
            preview.classList.remove('hidden');
            preview.classList.add('flex');
            filenameEl.textContent = file.name;
        }

        checkReady();
    });

    document.querySelectorAll('.required-field, .required-select').forEach(function(el) {
        el.addEventListener('input', checkReady);
        el.addEventListener('change', checkReady);
    });

    document.querySelectorAll('.sifat-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.sifat-label').forEach(function(lbl) {
                lbl.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                lbl.classList.add('border-gray-200', 'dark:border-gray-600');
            });
            this.closest('.sifat-label').classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            this.closest('.sifat-label').classList.remove('border-gray-200', 'dark:border-gray-600');
        });
    });

    checkReady();
</script>

@endsection