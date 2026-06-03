@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto pb-28">

    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Profil Saya</h2>
        <p class="text-sm text-gray-400 mt-0.5">Kelola informasi akun Anda</p>
    </div>

    <form id="main-form" action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data"
          data-konfirmasi data-tipe="edit" data-judul="Perbarui Profil?" data-pesan="Profil Anda akan diperbarui." data-label-ok="Ya, Perbarui">
        @csrf @method('PATCH')

        {{-- Section: Foto Profil --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Foto Profil</h3>
                    <p class="text-xs text-gray-400">Upload foto profil Anda (opsional)</p>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-6">
                    {{-- Preview foto --}}
                    <div class="relative flex-shrink-0">
                        <div id="foto-preview-wrap" class="w-24 h-24 rounded-2xl overflow-hidden border-2 border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            @if($user->foto)
                            <img id="foto-preview" src="{{ Storage::url($user->foto) }}" alt="Foto Profil"
                                 class="w-full h-full object-cover">
                            @else
                            <div id="foto-placeholder" class="flex items-center justify-center w-full h-full">
                                <span class="text-3xl font-bold text-gray-400 dark:text-gray-500">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                            <img id="foto-preview" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                            @endif
                        </div>
                    </div>

                    {{-- Upload area --}}
                    <div class="flex-1 space-y-3">
                        <label class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <span id="foto-btn-text">{{ $user->foto ? 'Ganti Foto' : 'Upload Foto' }}</span>
                            <input type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="hidden"
                                   id="foto-input" onchange="previewFoto(this)">
                        </label>

                        @if($user->foto)
                        <button type="button" onclick="hapusFoto()"
                                class="inline-flex items-center gap-1.5 text-xs text-red-500 hover:text-red-700 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Foto
                        </button>
                        @endif

                        <input type="hidden" name="hapus_foto" id="hapus-foto-input" value="0">

                        <p class="text-xs text-gray-400">JPG, PNG, atau WEBP. Maks 2MB.</p>
                        <p id="foto-filename" class="hidden text-xs text-green-600 font-medium"></p>

                        @error('foto')
                        <p class="text-red-500 text-xs flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Info Akun (readonly) --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400 px-2.5 py-0.5 rounded-full font-medium">{{ ucfirst($user->role) }}</span>
                    @if($user->jabatan)
                    <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2.5 py-0.5 rounded-full">{{ $user->jabatan->nama_jabatan }}</span>
                    @endif
                    @if($user->bagian)
                    <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2.5 py-0.5 rounded-full">{{ $user->bagian->nama_bagian }}</span>
                    @endif
                </div>
            </div>
            <div class="px-6 py-4 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">NIP</p>
                    <p class="text-sm font-mono text-gray-700 dark:text-gray-300">{{ $user->nip ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Email</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        {{-- Section: Nama --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Informasi Dasar</h3>
                    <p class="text-xs text-gray-400">Nama yang ditampilkan di sistem</p>
                </div>
            </div>
            <div class="p-6">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                    Nama Lengkap <span class="text-red-500 normal-case">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-400 @enderror"
                       placeholder="Nama lengkap Anda">
                @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Section: Password --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Ubah Kata Sandi</h3>
                    <p class="text-xs text-gray-400">Kosongkan jika tidak ingin mengubah</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Password Baru</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter"
                           class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('password') border-red-400 @enderror">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                           class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </div>
        </div>

        <div class="flex justify-start">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

    </form>
</div>

{{-- Sticky Bar --}}
<div class="fixed bottom-0 left-64 right-0 z-50">
    <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-2xl px-6 py-4">
        <div class="max-w-2xl mx-auto flex items-center justify-between gap-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">Simpan perubahan profil</p>
            <button type="submit" form="main-form"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        var file   = input.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            // Tampilkan preview
            var img = document.getElementById('foto-preview');
            var placeholder = document.getElementById('foto-placeholder');

            img.src = e.target.result;
            img.classList.remove('hidden');

            if (placeholder) placeholder.classList.add('hidden');

            // Update text
            document.getElementById('foto-btn-text').textContent = 'Ganti Foto';
            var filenameEl = document.getElementById('foto-filename');
            filenameEl.textContent = '✓ ' + file.name;
            filenameEl.classList.remove('hidden');

            // Reset hapus
            document.getElementById('hapus-foto-input').value = '0';
        };

        reader.readAsDataURL(file);
    }
}

function hapusFoto() {
    document.getElementById('hapus-foto-input').value = '1';
    document.getElementById('foto-input').value = '';

    var img = document.getElementById('foto-preview');
    var placeholder = document.getElementById('foto-placeholder');

    img.classList.add('hidden');
    img.src = '#';

    if (placeholder) {
        placeholder.classList.remove('hidden');
    } else {
        // Buat placeholder baru
        var wrap = document.getElementById('foto-preview-wrap');
        var newPlaceholder = document.createElement('div');
        newPlaceholder.id = 'foto-placeholder';
        newPlaceholder.className = 'flex items-center justify-center w-full h-full';
        newPlaceholder.innerHTML = '<span class="text-3xl font-bold text-gray-400 dark:text-gray-500">{{ strtoupper(substr($user->name, 0, 1)) }}</span>';
        wrap.appendChild(newPlaceholder);
    }

    document.getElementById('foto-btn-text').textContent = 'Upload Foto';
    document.getElementById('foto-filename').classList.add('hidden');
}
</script>

@endsection