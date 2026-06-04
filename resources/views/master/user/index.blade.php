@extends('layouts.app')
@section('title', 'Pengguna')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Kelola Pengguna</h2>
        <p class="text-sm text-gray-400 mt-0.5">Manajemen akun pengguna sistem</p>
    </div>
    <div class="flex items-center gap-2">
        {{-- Search --}}
        <form method="GET" action="{{ route('master.user.index') }}" class="relative">
            @foreach(request()->except(['search','page']) as $key => $val)
            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, email..."
                   class="w-52 border border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        </form>

        {{-- Filter --}}
        <button onclick="toggleFilter()"
                class="relative inline-flex items-center gap-2 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            Filter
            @php $filterCount = count(array_filter(request()->only(['role','jabatan_id','bagian_id','status_aktif']))); @endphp
            @if($filterCount > 0)
            <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-blue-600 text-white text-xs rounded-full flex items-center justify-center font-bold leading-none">{{ $filterCount }}</span>
            @endif
        </button>

        {{-- Tambah — admin only --}}
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('master.user.create') }}"
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah
        </a>
        @endif
    </div>
</div>

{{-- Filter Pills --}}
@if($filterCount > 0 || request('search'))
<div class="flex flex-wrap items-center gap-2 mb-4">
    <span class="text-xs text-gray-400">Filter aktif:</span>
    @if(request('search'))
    <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2.5 py-1 rounded-full">
        "{{ request('search') }}"
        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="hover:opacity-70 font-bold">x</a>
    </span>
    @endif
    @if(request('role'))
    <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2.5 py-1 rounded-full">
        Role: {{ ucfirst(request('role')) }}
        <a href="{{ request()->fullUrlWithQuery(['role' => null]) }}" class="hover:opacity-70 font-bold">x</a>
    </span>
    @endif
    @if(request('jabatan_id'))
    <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2.5 py-1 rounded-full">
        Jabatan: {{ $jabatans->find(request('jabatan_id'))->nama_jabatan ?? '-' }}
        <a href="{{ request()->fullUrlWithQuery(['jabatan_id' => null]) }}" class="hover:opacity-70 font-bold">x</a>
    </span>
    @endif
    @if(request('bagian_id'))
    <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2.5 py-1 rounded-full">
        Bagian: {{ $bagians->find(request('bagian_id'))->nama_bagian ?? '-' }}
        <a href="{{ request()->fullUrlWithQuery(['bagian_id' => null]) }}" class="hover:opacity-70 font-bold">x</a>
    </span>
    @endif
    @if(request('status_aktif'))
    <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2.5 py-1 rounded-full">
        Status: {{ ucfirst(request('status_aktif')) }}
        <a href="{{ request()->fullUrlWithQuery(['status_aktif' => null]) }}" class="hover:opacity-70 font-bold">x</a>
    </span>
    @endif
    <a href="{{ route('master.user.index') }}" class="text-xs text-red-500 hover:text-red-700 hover:underline ml-1">Hapus semua</a>
</div>
@endif

{{-- Widget Statistik --}}
<div class="grid grid-cols-3 gap-3 mb-3">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center cursor-pointer hover:border-gray-400 transition {{ !request('role') && !request('status_aktif') ? 'border-blue-500 bg-blue-50/50 dark:bg-blue-900/10' : '' }}"
         onclick="window.location='{{ route('master.user.index', request()->except(['role','status_aktif','page'])) }}'">
        <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalUser }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Total User</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center cursor-pointer hover:border-green-400 transition {{ request('status_aktif') === 'aktif' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : '' }}"
         onclick="window.location='{{ route('master.user.index', array_merge(request()->except(['page']), ['status_aktif' => 'aktif'])) }}'">
        <p class="text-2xl font-bold text-green-600">{{ $countAktif }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Aktif</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center cursor-pointer hover:border-red-400 transition {{ request('status_aktif') === 'nonaktif' ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : '' }}"
         onclick="window.location='{{ route('master.user.index', array_merge(request()->except(['page']), ['status_aktif' => 'nonaktif'])) }}'">
        <p class="text-2xl font-bold text-red-500">{{ $countNonaktif }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Nonaktif</p>
    </div>
</div>

{{-- Widget Role --}}
<div class="grid grid-cols-5 gap-3 mb-5">
    @php
        $roleWidgets = [
            ['role' => 'admin',    'label' => 'Admin',    'count' => $countAdmin,    'color' => 'blue',   'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            ['role' => 'direktur', 'label' => 'Direktur', 'count' => $countDirektur, 'color' => 'purple', 'icon' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['role' => 'kabag',    'label' => 'Kabag',    'count' => $countKabag,    'color' => 'yellow', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['role' => 'kasubbag', 'label' => 'Kasubag',  'count' => $countKasubag,  'color' => 'teal',   'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['role' => 'staff',    'label' => 'Staff',    'count' => $countStaff,    'color' => 'gray',   'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ];
    @endphp

    @foreach($roleWidgets as $w)
    @php $isActive = request('role') === $w['role']; @endphp
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 cursor-pointer hover:border-{{ $w['color'] }}-400 hover:shadow-sm transition
        {{ $isActive ? 'border-'.$w['color'].'-500 bg-'.$w['color'].'-50 dark:bg-'.$w['color'].'-900/20 shadow-sm' : '' }}"
         onclick="window.location='{{ route('master.user.index', ['role' => $w['role']]) }}'">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-{{ $w['color'] }}-100 dark:bg-{{ $w['color'] }}-900/40 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-{{ $w['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $w['icon'] }}"/>
                </svg>
            </div>
            <div class="text-left">
                <p class="text-xl font-bold text-{{ $w['color'] }}-600">{{ $w['count'] }}</p>
                <p class="text-xs text-gray-400">{{ $w['label'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Overlay Filter --}}
<div id="filter-overlay" onclick="toggleFilter()" class="fixed inset-0 bg-black/40 z-40 hidden"></div>

{{-- Filter Slide-over --}}
<div id="filter-panel"
     class="fixed top-0 right-0 h-full w-80 bg-white dark:bg-gray-800 shadow-2xl z-50 flex flex-col filter-slide">

    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex-shrink-0">
        <div>
            <h3 class="font-semibold text-gray-800 dark:text-white text-sm">Filter Pengguna</h3>
            <p class="text-xs text-gray-400 mt-0.5">Saring data sesuai kebutuhan</p>
        </div>
        <button onclick="toggleFilter()" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <form method="GET" action="{{ route('master.user.index') }}" class="flex flex-col flex-1 overflow-hidden">
        @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif

        <div class="flex-1 overflow-y-auto px-5 py-5 space-y-5">

            {{-- Jabatan --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Jabatan</label>
                <select name="jabatan_id" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">Semua Jabatan</option>
                    @foreach($jabatans as $j)
                    <option value="{{ $j->id }}" {{ request('jabatan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Bagian --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Bagian</label>
                <select name="bagian_id" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">Semua Bagian</option>
                    @foreach($bagians->whereNull('parent_id') as $parent)
                    <option value="{{ $parent->id }}" {{ request('bagian_id') == $parent->id ? 'selected' : '' }}>{{ $parent->nama_bagian }}</option>
                        @foreach($bagians->where('parent_id', $parent->id) as $child)
                        <option value="{{ $child->id }}" {{ request('bagian_id') == $child->id ? 'selected' : '' }}>&nbsp;&nbsp;└ {{ $child->nama_bagian }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            {{-- Status Aktif --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Status</label>
                <div class="grid grid-cols-3 gap-1.5">
                    @foreach(['' => 'Semua', 'aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'] as $val => $label)
                    <label class="filter-radio-label flex items-center justify-center px-3 py-2 border rounded-lg cursor-pointer transition text-center {{ request('status_aktif') === $val ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                        <input type="radio" name="status_aktif" value="{{ $val }}" {{ request('status_aktif') === $val ? 'checked' : '' }} class="hidden">
                        <span class="text-xs {{ request('status_aktif') === $val ? 'text-blue-600 font-medium' : 'text-gray-600 dark:text-gray-300' }}">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700 flex gap-2 flex-shrink-0">
            <a href="{{ route('master.user.index') }}"
               class="flex-1 text-center text-sm text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 py-2.5 rounded-lg transition font-medium">
                Reset
            </a>
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2.5 rounded-lg transition">
                Terapkan
            </button>
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-10">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Pengguna</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">NIP</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Jabatan</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Bagian</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                    @if(auth()->user()->role === 'admin')
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
                    @endif 
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($users as $i => $u)
            @php
                $roleColor = match($u->role) {
                    'admin'    => 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                    'direktur' => 'bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
                    'kabag'    => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400',
                    'kasubbag' => 'bg-teal-50 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400',
                    default    => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                };
            @endphp
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                <td class="px-4 py-3 text-gray-400 text-xs">{{ $users->firstItem() + $i }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg overflow-hidden flex-shrink-0 border border-gray-200 dark:border-gray-600">
                            @if($u->foto)
                            <img src="{{ Storage::url($u->foto) }}" alt="" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                                <span class="text-xs font-bold text-blue-600">{{ strtoupper(substr($u->name, 0, 1)) }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-800 dark:text-white truncate">{{ $u->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $u->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 font-mono text-xs text-gray-500 dark:text-gray-400">{{ $u->nip ?? '-' }}</td>
                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-300">{{ $u->jabatan->nama_jabatan ?? '-' }}</td>
                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-300 max-w-[150px] truncate">{{ $u->bagian->nama_bagian ?? '-' }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $roleColor }}">{{ ucfirst($u->role) }}</span>
                </td>
                <td class="px-4 py-3">
                    @if($u->is_active)
                    <span class="inline-flex items-center gap-1 text-xs text-green-600"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif</span>
                    @else
                    <span class="inline-flex items-center gap-1 text-xs text-red-500"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-center gap-1">
                        {{-- Detail — semua role --}}
                        <button onclick="document.getElementById('user-modal-{{ $u->id }}').classList.remove('hidden')"
                                title="Detail" class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                        {{-- Edit & Hapus — admin only --}}
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('master.user.edit', $u) }}" title="Edit" class="p-1.5 text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        @if($u->id !== auth()->id())
                        <form action="{{ route('master.user.destroy', $u) }}" method="POST" class="inline"
                            data-konfirmasi data-tipe="hapus" data-judul="Hapus Pengguna?" data-pesan="Pengguna {{ $u->name }} akan dihapus." data-label-ok="Ya, Hapus">
                            @csrf @method('DELETE')
                            <button type="submit" title="Hapus" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                        @endif
                        @endif
                    </div>
                </td>
            </tr>

            {{-- Modal Detail --}}
            <tr class="hidden-row">
                <td colspan="8" class="p-0 border-0">
                    <div id="user-modal-{{ $u->id }}" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
                        onclick="if(event.target===this) this.classList.add('hidden')">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col">
                            <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 flex-shrink-0">
                                <h4 class="font-semibold text-gray-800 dark:text-white">Detail Pengguna</h4>
                                <button onclick="document.getElementById('user-modal-{{ $u->id }}').classList.add('hidden')"
                                        class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="flex-1 overflow-y-auto px-6 py-5">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden flex-shrink-0 border-2 border-gray-200 dark:border-gray-600">
                                        @if($u->foto)
                                        <img src="{{ Storage::url($u->foto) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                        <div class="w-full h-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                                            <span class="text-xl font-bold text-blue-600">{{ strtoupper(substr($u->name, 0, 1)) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-base font-semibold text-gray-800 dark:text-white">{{ $u->name }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs px-2.5 py-0.5 rounded-full font-medium {{ $roleColor }}">{{ ucfirst($u->role) }}</span>
                                            @if($u->is_active)
                                            <span class="inline-flex items-center gap-1 text-xs text-green-600"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif</span>
                                            @else
                                            <span class="inline-flex items-center gap-1 text-xs text-red-500"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                                    <div><p class="text-xs text-gray-400 mb-0.5">NIP</p><p class="font-mono text-gray-700 dark:text-gray-300">{{ $u->nip ?? '-' }}</p></div>
                                    <div><p class="text-xs text-gray-400 mb-0.5">Email</p><p class="text-gray-700 dark:text-gray-300 truncate">{{ $u->email }}</p></div>
                                    <div><p class="text-xs text-gray-400 mb-0.5">Jabatan</p><p class="text-gray-700 dark:text-gray-300">{{ $u->jabatan->nama_jabatan ?? '-' }}</p></div>
                                    <div><p class="text-xs text-gray-400 mb-0.5">Bagian</p><p class="text-gray-700 dark:text-gray-300">{{ $u->bagian->nama_bagian ?? '-' }}</p></div>
                                    <div><p class="text-xs text-gray-400 mb-0.5">Terdaftar</p><p class="text-gray-700 dark:text-gray-300">{{ $u->created_at->format('d/m/Y H:i') }}</p></div>
                                    <div><p class="text-xs text-gray-400 mb-0.5">Terakhir Update</p><p class="text-gray-700 dark:text-gray-300">{{ $u->updated_at->diffForHumans() }}</p></div>
                                </div>
                            </div>
                            <div class="px-6 py-4 border-t dark:border-gray-700 flex gap-2 flex-shrink-0">
                                @if(auth()->user()->role === 'admin')
                                <a href="{{ route('master.user.edit', $u) }}" class="inline-flex items-center gap-2 text-sm bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-4 py-2 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                @endif
                                <button onclick="document.getElementById('user-modal-{{ $u->id }}').classList.add('hidden')"
                                        class="text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white text-gray-700 px-4 py-2 rounded-lg transition ml-auto">Tutup</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="8" class="px-6 py-16 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="text-sm">Tidak ada pengguna ditemukan</p>
                </td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
        {{ $users->links() }}
    </div>
    @endif
</div>

<script>
function toggleFilter() {
    var panel   = document.getElementById('filter-panel');
    var overlay = document.getElementById('filter-overlay');

    if (panel.classList.contains('filter-open')) {
        panel.classList.remove('filter-open');
        overlay.classList.add('hidden');
    } else {
        panel.classList.add('filter-open');
        overlay.classList.remove('hidden');
    }
}

document.querySelectorAll('.filter-radio-label input[type="radio"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var name = this.name;
        document.querySelectorAll('.filter-radio-label input[name="' + name + '"]').forEach(function(r) {
            var lbl = r.closest('.filter-radio-label');
            if (r.checked) {
                lbl.classList.add('border-blue-500', 'bg-blue-50');
                lbl.classList.remove('border-gray-200');
            } else {
                lbl.classList.remove('border-blue-500', 'bg-blue-50');
                lbl.classList.add('border-gray-200');
            }
        });
    });
});
</script>

<style>
    /* Filter slide-over */
    .filter-slide {
        transform: translateX(100%);
        transition: transform 0.3s ease;
    }
    .filter-slide.filter-open {
        transform: translateX(0%);
    }
</style>

@endsection