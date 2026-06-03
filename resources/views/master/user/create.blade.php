@extends('layouts.app')
@section('title', 'Tambah Pengguna')

@section('content')
<div class="max-w-2xl mx-auto pb-28">

    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
            <a href="{{ route('master.user.index') }}" class="hover:text-blue-600 transition">Pengguna</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 dark:text-gray-300">Tambah Baru</span>
        </div>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Tambah Pengguna</h2>
    </div>

    <form id="main-form" action="{{ route('master.user.store') }}" method="POST" enctype="multipart/form-data"
          data-konfirmasi data-tipe="simpan" data-judul="Tambah Pengguna?" data-pesan="Pengguna baru akan dibuat." data-label-ok="Ya, Tambahkan">
        @csrf

        {{-- Identitas --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Identitas Pengguna</h3>
                    <p class="text-xs text-gray-400">Nama, NIP, dan email</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap pegawai"
                           class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">NIP <span class="text-red-500">*</span></label>
                    <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai"
                           class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('nip') border-red-400 @enderror">
                    @error('nip')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@perumda.local"
                           class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Jabatan & Bagian --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Jabatan & Bagian</h3>
                    <p class="text-xs text-gray-400">Jabatan menentukan role otomatis</p>
                </div>
            </div>
            <div class="p-6 space-y-5">

                {{-- Jabatan --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Jabatan <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach($jabatans as $jabatan)
                        @php
                            $iconColor = match($jabatan->level) {
                                1 => 'bg-purple-100 text-purple-600 dark:bg-purple-900/40',
                                2 => 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/40',
                                3 => 'bg-teal-100 text-teal-600 dark:bg-teal-900/40',
                                default => 'bg-gray-100 text-gray-500 dark:bg-gray-700',
                            };
                            $roleName = match($jabatan->level) {
                                1 => 'Direktur',
                                2 => 'Kabag',
                                3 => 'Kasubag',
                                default => 'Staff',
                            };
                            $isActive = old('jabatan_id') == $jabatan->id;
                        @endphp
                        <label class="jabatan-label flex flex-col items-center gap-1.5 px-3 py-3 border rounded-xl cursor-pointer transition text-center
                            {{ $isActive ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <input type="radio" name="jabatan_id" value="{{ $jabatan->id }}"
                                   {{ $isActive ? 'checked' : '' }}
                                   class="hidden jabatan-radio"
                                   data-level="{{ $jabatan->level }}">
                            <div class="w-8 h-8 rounded-lg {{ $iconColor }} flex items-center justify-center">
                                <span class="text-xs font-bold">{{ strtoupper(substr($roleName, 0, 1)) }}</span>
                            </div>
                            <span class="text-xs font-medium {{ $isActive ? 'text-blue-600' : 'text-gray-600 dark:text-gray-300' }}">{{ $jabatan->nama_jabatan }}</span>
                            <span class="text-xs text-gray-400">Role: {{ $roleName }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('jabatan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Info role otomatis --}}
                <div id="role-info" class="hidden">
                    <div class="flex items-center gap-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg px-3 py-2">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-blue-600 dark:text-blue-400">Role akan otomatis diisi: <strong id="role-auto-text"></strong></p>
                    </div>
                </div>

                {{-- Bagian --}}
                <div id="bagian-section" class="hidden">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                        Bagian <span id="bagian-required-mark" class="text-red-500">*</span>
                    </label>
                    <select name="bagian_id" id="bagian-select"
                            class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">-- Pilih Bagian --</option>
                    </select>
                    @error('bagian_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        {{-- Foto --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-yellow-100 dark:bg-yellow-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Foto Profil</h3>
                    <p class="text-xs text-gray-400">Opsional</p>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                        <div id="foto-placeholder">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <img id="foto-preview" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                    </div>
                    <div class="flex-1 space-y-2">
                        <label class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium px-4 py-2 rounded-lg transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <span id="foto-btn-text">Pilih Foto</span>
                            <input type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewFoto(this)">
                        </label>
                        <p class="text-xs text-gray-400">JPG, PNG, WEBP. Maks 2MB.</p>
                        <p id="foto-filename" class="hidden text-xs text-green-600 font-medium"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Password --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Kata Sandi</h3>
                    <p class="text-xs text-gray-400">Password untuk login</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter"
                           class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition @error('password') border-red-400 @enderror">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Konfirmasi <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password"
                           class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </div>
        </div>

        <a href="{{ route('master.user.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Batal
        </a>
    </form>
</div>

{{-- Sticky Bar --}}
<div id="sticky-bar" class="fixed bottom-0 left-64 right-0 z-50 translate-y-full transition-transform duration-300">
    <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-2xl px-6 py-4">
        <div class="max-w-2xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-sm text-green-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-medium">Siap disimpan</span>
            </div>
            <button type="submit" form="main-form"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Pengguna
            </button>
        </div>
    </div>
</div>

<script>
// Data bagian dari server
var bagianKabag = @json($bagianKabag->map(fn($b) => ['id' => $b->id, 'nama' => $b->nama_bagian]));
var bagianKasubag = @json($bagianKasubag->map(fn($b) => ['id' => $b->id, 'nama' => $b->nama_bagian]));
var selectedBagian = '{{ old("bagian_id") }}';

var roleMap = { 1: 'Direktur', 2: 'Kabag', 3: 'Kasubag', 4: 'Staff' };
var jabatanSelected = false;

function onJabatanChange() {
    var checked = document.querySelector('.jabatan-radio:checked');
    if (!checked) return;

    jabatanSelected = true;
    var level = parseInt(checked.dataset.level);
    var roleName = roleMap[level] || 'Staff';

    // Highlight label
    document.querySelectorAll('.jabatan-label').forEach(function(lbl) {
        lbl.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        lbl.classList.add('border-gray-200', 'dark:border-gray-600');
        lbl.querySelectorAll('span').forEach(function(s) {
            s.classList.remove('text-blue-600');
        });
    });
    var lbl = checked.closest('.jabatan-label');
    lbl.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
    lbl.classList.remove('border-gray-200', 'dark:border-gray-600');

    // Show role info
    document.getElementById('role-info').classList.remove('hidden');
    document.getElementById('role-auto-text').textContent = roleName;

    // Bagian section
    var bagianSection = document.getElementById('bagian-section');
    var bagianSelect  = document.getElementById('bagian-select');
    var requiredMark  = document.getElementById('bagian-required-mark');

    if (level === 2) {
        // Kabag → tampilkan bagian parent
        bagianSection.classList.remove('hidden');
        requiredMark.classList.remove('hidden');
        populateBagian(bagianKabag);
    } else if (level === 3) {
        // Kasubag → tampilkan sub bagian
        bagianSection.classList.remove('hidden');
        requiredMark.classList.remove('hidden');
        populateBagian(bagianKasubag);
    } else {
        // Direktur/Staff → sembunyikan bagian
        bagianSection.classList.add('hidden');
        bagianSelect.value = '';
    }

    checkReady();
}

function populateBagian(data) {
    var select = document.getElementById('bagian-select');
    select.innerHTML = '<option value="">-- Pilih Bagian --</option>';
    data.forEach(function(b) {
        var opt = document.createElement('option');
        opt.value = b.id;
        opt.textContent = b.nama;
        if (selectedBagian == b.id) opt.selected = true;
        select.appendChild(opt);
    });
}

function checkReady() {
    var ready = true;
    document.querySelectorAll('.required-field').forEach(function(f) {
        if (!f.value.trim()) ready = false;
    });
    if (!jabatanSelected) ready = false;

    // Cek bagian wajib untuk kabag/kasubag
    var checked = document.querySelector('.jabatan-radio:checked');
    if (checked) {
        var level = parseInt(checked.dataset.level);
        if ((level === 2 || level === 3) && !document.getElementById('bagian-select').value) {
            ready = false;
        }
    }

    document.getElementById('sticky-bar').classList.toggle('translate-y-full', !ready);
}

function previewFoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('foto-preview').src = e.target.result;
            document.getElementById('foto-preview').classList.remove('hidden');
            document.getElementById('foto-placeholder').classList.add('hidden');
            document.getElementById('foto-btn-text').textContent = 'Ganti Foto';
            var fn = document.getElementById('foto-filename');
            fn.textContent = '✓ ' + input.files[0].name;
            fn.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Event listeners
document.querySelectorAll('.jabatan-radio').forEach(function(r) {
    r.addEventListener('change', onJabatanChange);
});
document.querySelectorAll('.required-field').forEach(function(el) {
    el.addEventListener('input', checkReady);
});
document.getElementById('bagian-select').addEventListener('change', checkReady);

// Init
onJabatanChange();
checkReady();
</script>

@endsection