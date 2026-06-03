@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('content')
<div class="max-w-2xl mx-auto pb-28">

    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
            <a href="{{ route('master.user.index') }}" class="hover:text-blue-600 transition">Pengguna</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 dark:text-gray-300">Edit</span>
        </div>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Edit Pengguna</h2>
    </div>

    <form id="main-form" action="{{ route('master.user.update', $user) }}" method="POST" enctype="multipart/form-data"
          data-konfirmasi data-tipe="edit" data-judul="Perbarui Pengguna?" data-pesan="Data pengguna akan diperbarui." data-label-ok="Ya, Perbarui">
        @csrf @method('PUT')

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
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">NIP <span class="text-red-500">*</span></label>
                    <input type="text" name="nip" value="{{ old('nip', $user->nip) }}"
                           class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @error('nip')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="required-field w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
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
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-2 uppercase tracking-wider">Jabatan <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach($jabatans as $jabatan)
                        @php
                            $iconColor = match($jabatan->level) {
                                1 => 'bg-purple-100 text-purple-600 dark:bg-purple-900/40',
                                2 => 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/40',
                                3 => 'bg-teal-100 text-teal-600 dark:bg-teal-900/40',
                                default => 'bg-gray-100 text-gray-500 dark:bg-gray-700',
                            };
                            $roleName = match($jabatan->level) { 1 => 'Direktur', 2 => 'Kabag', 3 => 'Kasubag', default => 'Staff' };
                            $isActive = old('jabatan_id', $user->jabatan_id) == $jabatan->id;
                        @endphp
                        <label class="jabatan-label flex flex-col items-center gap-1.5 px-3 py-3 border rounded-xl cursor-pointer transition text-center
                            {{ $isActive ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <input type="radio" name="jabatan_id" value="{{ $jabatan->id }}" {{ $isActive ? 'checked' : '' }}
                                   class="hidden jabatan-radio" data-level="{{ $jabatan->level }}">
                            <div class="w-8 h-8 rounded-lg {{ $iconColor }} flex items-center justify-center">
                                <span class="text-xs font-bold">{{ strtoupper(substr($roleName, 0, 1)) }}</span>
                            </div>
                            <span class="text-xs font-medium {{ $isActive ? 'text-blue-600' : 'text-gray-600 dark:text-gray-300' }}">{{ $jabatan->nama_jabatan }}</span>
                            <span class="text-xs text-gray-400">Role: {{ $roleName }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div id="role-info" class="hidden">
                    <div class="flex items-center gap-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg px-3 py-2">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-blue-600">Role otomatis: <strong id="role-auto-text"></strong></p>
                    </div>
                </div>

                <div id="bagian-section" class="hidden">
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Bagian <span id="bagian-required-mark" class="text-red-500">*</span></label>
                    <select name="bagian_id" id="bagian-select"
                            class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">-- Pilih Bagian --</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Status Akun --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-5">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Status Akun</h3>
                    <p class="text-xs text-gray-400">Aktifkan atau nonaktifkan akun pengguna</p>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Akun Aktif</p>
                        <p class="text-xs text-gray-400 mt-0.5">User yang nonaktif tidak bisa login ke sistem</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                            class="sr-only peer" id="toggle-aktif">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        <span id="toggle-label" class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300"></span>
                    </label>
                </div>

                {{-- Info jika nonaktif --}}
                <div id="nonaktif-warning" class="hidden mt-4 flex items-start gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg px-3 py-2.5">
                    <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-xs text-red-600 dark:text-red-400">Pengguna ini tidak akan bisa login ke sistem sampai diaktifkan kembali.</p>
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
                        @if($user->foto)
                        <img id="foto-preview" src="{{ Storage::url($user->foto) }}" alt="" class="w-full h-full object-cover">
                        <div id="foto-placeholder" class="hidden"></div>
                        @else
                        <div id="foto-placeholder"><span class="text-2xl font-bold text-gray-400">{{ strtoupper(substr($user->name, 0, 1)) }}</span></div>
                        <img id="foto-preview" src="#" alt="" class="hidden w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1 space-y-2">
                        <div class="flex items-center gap-2">
                            <label class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium px-4 py-2 rounded-lg transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <span id="foto-btn-text">{{ $user->foto ? 'Ganti Foto' : 'Pilih Foto' }}</span>
                                <input type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewFoto(this)">
                            </label>
                            @if($user->foto)
                            <button type="button" onclick="hapusFoto()" class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                            @endif
                        </div>
                        <input type="hidden" name="hapus_foto" id="hapus-foto-input" value="0">
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
                    <p class="text-xs text-gray-400">Kosongkan jika tidak diubah</p>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Password Baru</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                           class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Konfirmasi</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password"
                           class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </div>
        </div>

        <a href="{{ route('master.user.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Batal
        </a>
    </form>
</div>

<div class="fixed bottom-0 left-64 right-0 z-50">
    <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-2xl px-6 py-4">
        <div class="max-w-2xl mx-auto flex items-center justify-between gap-4">
            <p class="text-sm text-gray-500">Perbarui data pengguna</p>
            <button type="submit" form="main-form"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Perbarui
            </button>
        </div>
    </div>
</div>

<script>
var bagianKabag = @json($bagianKabag->map(fn($b) => ['id' => $b->id, 'nama' => $b->nama_bagian]));
var bagianKasubag = @json($bagianKasubag->map(fn($b) => ['id' => $b->id, 'nama' => $b->nama_bagian]));
var selectedBagian = '{{ old("bagian_id", $user->bagian_id) }}';
var roleMap = { 1: 'Direktur', 2: 'Kabag', 3: 'Kasubag', 4: 'Staff' };

function onJabatanChange() {
    var checked = document.querySelector('.jabatan-radio:checked');
    if (!checked) return;
    var level = parseInt(checked.dataset.level);
    var roleName = roleMap[level] || 'Staff';

    document.querySelectorAll('.jabatan-label').forEach(function(lbl) {
        lbl.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        lbl.classList.add('border-gray-200', 'dark:border-gray-600');
    });
    checked.closest('.jabatan-label').classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
    checked.closest('.jabatan-label').classList.remove('border-gray-200', 'dark:border-gray-600');

    document.getElementById('role-info').classList.remove('hidden');
    document.getElementById('role-auto-text').textContent = roleName;

    var bagianSection = document.getElementById('bagian-section');
    var bagianSelect  = document.getElementById('bagian-select');

    if (level === 2) {
        bagianSection.classList.remove('hidden');
        populateBagian(bagianKabag);
    } else if (level === 3) {
        bagianSection.classList.remove('hidden');
        populateBagian(bagianKasubag);
    } else {
        bagianSection.classList.add('hidden');
        bagianSelect.value = '';
    }
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

function previewFoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('foto-preview').src = e.target.result;
            document.getElementById('foto-preview').classList.remove('hidden');
            var ph = document.getElementById('foto-placeholder');
            if (ph) ph.classList.add('hidden');
            document.getElementById('foto-btn-text').textContent = 'Ganti Foto';
            var fn = document.getElementById('foto-filename');
            fn.textContent = '✓ ' + input.files[0].name;
            fn.classList.remove('hidden');
            document.getElementById('hapus-foto-input').value = '0';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function hapusFoto() {
    document.getElementById('hapus-foto-input').value = '1';
    document.getElementById('foto-preview').classList.add('hidden');
    var ph = document.getElementById('foto-placeholder');
    if (ph) ph.classList.remove('hidden');
    document.getElementById('foto-btn-text').textContent = 'Pilih Foto';
    document.getElementById('foto-filename').classList.add('hidden');
}

document.querySelectorAll('.jabatan-radio').forEach(function(r) { r.addEventListener('change', onJabatanChange); });
onJabatanChange();

// Toggle status label
var toggleAktif = document.getElementById('toggle-aktif');
var toggleLabel = document.getElementById('toggle-label');
var nonaktifWarning = document.getElementById('nonaktif-warning');

function updateToggleLabel() {
    if (toggleAktif.checked) {
        toggleLabel.textContent = 'Aktif';
        toggleLabel.classList.remove('text-red-500');
        toggleLabel.classList.add('text-gray-700', 'dark:text-gray-300');
        nonaktifWarning.classList.add('hidden');
    } else {
        toggleLabel.textContent = 'Nonaktif';
        toggleLabel.classList.add('text-red-500');
        toggleLabel.classList.remove('text-gray-700', 'dark:text-gray-300');
        nonaktifWarning.classList.remove('hidden');
    }
}

toggleAktif.addEventListener('change', updateToggleLabel);
updateToggleLabel();
</script>

@endsection