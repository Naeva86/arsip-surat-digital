@extends('layouts.app')
@section('title', 'Tambah Surat Keluar')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
            <a href="{{ route('surat-keluar.index') }}" class="hover:text-blue-600 transition">Surat Keluar</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-600 dark:text-gray-300">Tambah Baru</span>
        </div>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tambah Surat Keluar</h2>
        <p class="text-sm text-gray-400 mt-0.5">Isi semua kolom yang diperlukan dengan benar</p>
    </div>

    <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data"
        data-konfirmasi
        data-tipe="simpan"
        data-judul="Simpan Surat Keluar?"
        data-pesan="Pastikan semua data sudah benar sebelum disimpan."
        data-label-ok="Ya, Simpan">
        @csrf

        {{-- Section 1: Identitas Surat --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Identitas Surat</h3>
                    <p class="text-xs text-gray-400">Nomor, perihal, dan penerima surat</p>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                        Nomor Surat <span class="text-red-500 normal-case">*</span>
                    </label>
                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}"
                           placeholder="cth: 001/PERUMDA-TDA/UMUM/V/2025"
                           class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('nomor_surat') border-red-400 @enderror">
                    @error('nomor_surat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                        Penerima <span class="text-red-500 normal-case">*</span>
                    </label>
                    <input type="text" name="penerima" value="{{ old('penerima') }}"
                           placeholder="Nama instansi atau perorangan penerima..."
                           class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('penerima') border-red-400 @enderror">
                    @error('penerima')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                        Perihal <span class="text-red-500 normal-case">*</span>
                    </label>
                    <input type="text" name="judul_surat" value="{{ old('judul_surat') }}"
                           placeholder="Tuliskan perihal surat..."
                           class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('judul_surat') border-red-400 @enderror">
                    @error('judul_surat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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
                    <p class="text-xs text-gray-400">Tanggal, sifat, dan kategori</p>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                        Tanggal Surat <span class="text-red-500 normal-case">*</span>
                    </label>
                    <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}"
                           class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @error('tanggal_surat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                        Tanggal Arsip <span class="text-red-500 normal-case">*</span>
                    </label>
                    <input type="date" name="tanggal_arsip" value="{{ old('tanggal_arsip', date('Y-m-d')) }}"
                           class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @error('tanggal_arsip')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                        Sifat Surat <span class="text-red-500 normal-case">*</span>
                    </label>
                    <div class="flex flex-col gap-2">
                        @foreach(['biasa' => ['label' => 'Biasa', 'color' => 'gray'], 'penting' => ['label' => 'Penting', 'color' => 'yellow'], 'rahasia' => ['label' => 'Rahasia', 'color' => 'purple']] as $val => $opt)
                        <label class="sifat-label flex items-center gap-2 px-3 py-2.5 border rounded-lg cursor-pointer transition
                            {{ old('sifat') === $val ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <input type="radio" name="sifat" value="{{ $val }}"
                                   {{ old('sifat', 'biasa') === $val ? 'checked' : '' }}
                                   class="hidden sifat-radio">
                            <span class="w-3 h-3 rounded-full flex-shrink-0
                                {{ $opt['color'] === 'gray'   ? 'bg-gray-400' : '' }}
                                {{ $opt['color'] === 'yellow' ? 'bg-yellow-400' : '' }}
                                {{ $opt['color'] === 'purple' ? 'bg-purple-500' : '' }}"></span>
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ $opt['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Kategori</label>
                        <select name="kategori_id"
                                class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Keterangan</label>
                        <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                               placeholder="Opsional"
                               class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

            </div>
        </div>

        {{-- Section 3: Upload --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">File Surat</h3>
                    <p class="text-xs text-gray-400">Upload file digital surat keluar</p>
                </div>
            </div>
            <div class="p-6">
                <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/30 transition group">
                    <div id="upload-placeholder" class="flex flex-col items-center gap-2 text-center">
                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600 group-hover:text-blue-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 group-hover:text-blue-500 transition">
                                Klik untuk upload atau drag & drop
                            </p>
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
                    <input type="file" name="file_path" accept=".pdf" class="hidden"
                           onchange="previewFile(this)">
                </label>
            </div>
        </div>

        {{-- Action --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('surat-keluar.index') }}"
               class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Surat Keluar
            </button>
        </div>

    </form>
</div>

<script>
function previewFile(input) {
    if (input.files && input.files[0]) {
        document.getElementById('upload-placeholder').classList.add('hidden');
        const preview = document.getElementById('upload-preview');
        preview.classList.remove('hidden');
        preview.classList.add('flex');
        document.getElementById('upload-filename').textContent = input.files[0].name;
    }
}

document.querySelectorAll('.sifat-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.sifat-label').forEach(lbl => {
            lbl.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            lbl.classList.add('border-gray-200', 'dark:border-gray-600');
        });
        this.closest('.sifat-label').classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        this.closest('.sifat-label').classList.remove('border-gray-200', 'dark:border-gray-600');
    });
});
</script>

@endsection