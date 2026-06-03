@extends('layouts.app')
@section('title', 'Kotak Disposisi')

@section('content')

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Kotak Disposisi</h2>
        <p class="text-sm text-gray-400 mt-0.5">
            @if(auth()->user()->role === 'direktur')
                Surat masuk yang menunggu persetujuan Anda
            @elseif(auth()->user()->role === 'kabag')
                Disposisi yang diarahkan ke bagian Anda
            @elseif(auth()->user()->role === 'kasubbag')
                Disposisi yang diteruskan kepada Anda
            @else
                Semua disposisi masuk
            @endif
        </p>
    </div>
    <div class="flex items-center gap-2">
        {{-- Search --}}
        <form method="GET" action="{{ route('disposisi.index') }}" class="relative">
            @if(request('status_filter'))<input type="hidden" name="status_filter" value="{{ request('status_filter') }}">@endif
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perihal, pengirim..."
                   class="w-48 border border-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
        </form>

        {{-- Filter status (kasubag) --}}
        @if(auth()->user()->role === 'kasubbag')
        <div class="flex items-center border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
            <a href="{{ route('disposisi.index') }}"
               class="px-3 py-2 text-xs font-medium transition {{ !request('status_filter') ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-500 hover:bg-gray-50' }}">Semua</a>
            <a href="{{ route('disposisi.index', ['status_filter' => 'menunggu']) }}"
               class="px-3 py-2 text-xs font-medium transition {{ request('status_filter') === 'menunggu' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-500 hover:bg-gray-50' }}">Belum Dibaca</a>
            <a href="{{ route('disposisi.index', ['status_filter' => 'selesai']) }}"
               class="px-3 py-2 text-xs font-medium transition {{ request('status_filter') === 'selesai' ? 'bg-green-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-500 hover:bg-gray-50' }}">Selesai</a>
        </div>
        @endif
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-10">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">No Agenda</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Perihal</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Pengirim</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Dari</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Sifat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($disposisis as $i => $d)
                @php
                    $sifatColor = match($d->suratMasuk->sifat ?? '') {
                        'urgent'  => 'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                        'penting' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400',
                        'rahasia' => 'bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
                        default   => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                    };
                    $sifatColorModal = match($d->suratMasuk->sifat ?? '') {
                        'urgent'  => 'bg-red-50 text-red-600',
                        'penting' => 'bg-yellow-50 text-yellow-600',
                        'rahasia' => 'bg-purple-50 text-purple-600',
                        default   => 'bg-gray-100 text-gray-500',
                    };
                    $statusBadge = match($d->status) {
                        'menunggu' => 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                        'dibaca'   => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400',
                        'selesai'  => 'bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400',
                        default    => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400',
                    };
                    $statusLabel = match($d->status) {
                        'menunggu' => 'Belum Dibaca',
                        'dibaca'   => 'Dibaca',
                        'selesai'  => 'Selesai',
                        default    => ucfirst($d->status),
                    };
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition {{ $d->status === 'menunggu' ? 'bg-blue-50/30 dark:bg-blue-900/10' : '' }}" id="row-{{ $d->id }}">
                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $disposisis->firstItem() + $i }}</td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">
                            {{ $d->suratMasuk->no_agenda ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 max-w-xs">
                        <div class="font-medium text-gray-800 dark:text-white truncate">{{ Str::limit($d->suratMasuk->judul_surat ?? '-', 40) }}</div>
                        @if($d->suratMasuk->kategori)
                        <div class="text-xs text-gray-400 mt-0.5">{{ $d->suratMasuk->kategori->nama_kategori }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $d->suratMasuk->pengirim ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-blue-600">{{ strtoupper(substr($d->dariUser->name ?? '?', 0, 1)) }}</span>
                            </div>
                            <span class="text-xs text-gray-600 dark:text-gray-300">{{ $d->dariUser->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $sifatColor }}">
                            {{ ucfirst($d->suratMasuk->sifat ?? '-') }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span id="status-badge-{{ $d->id }}" class="text-xs px-2.5 py-1 rounded-full font-medium {{ $statusBadge }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">

                            {{-- Detail popup — kasubag: auto selesai saat buka --}}
                            @if(auth()->user()->role === 'kasubbag')
                            <button onclick="bukaDetailKasubag({{ $d->id }})"
                                    title="Detail Surat"
                                    class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            @else
                            {{-- Detail popup — direktur/kabag --}}
                            <button onclick="document.getElementById('dispo-modal-{{ $d->id }}').classList.remove('hidden')"
                                    title="Detail Surat"
                                    class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            @endif

                            {{-- Proses Disposisi — Direktur & Kabag --}}
                            @if(in_array(auth()->user()->role, ['direktur', 'kabag', 'admin']))
                            <a href="{{ route('disposisi.show', $d) }}" title="Proses Disposisi"
                               class="p-1.5 text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </a>
                            @endif

                            {{-- Cetak — Kasubag --}}
                            @if(auth()->user()->role === 'kasubbag')
                            <a href="{{ route('disposisi.cetak', $d) }}" target="_blank" title="Cetak Disposisi"
                               class="p-1.5 text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                            </a>
                            @endif

                            {{-- File --}}
                            @if($d->suratMasuk->file_path)
                            <a href="{{ Storage::url($d->suratMasuk->file_path) }}" target="_blank" title="Lihat File"
                               class="p-1.5 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </a>
                            @endif

                        </div>
                    </td>
                </tr>

                {{-- Modal Detail Surat --}}
                <tr>
                    <td colspan="8" class="p-0 border-0">
                        <div id="dispo-modal-{{ $d->id }}"
                             class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
                             onclick="if(event.target===this) this.classList.add('hidden')">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">

                                <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 flex-shrink-0">
                                    <div>
                                        <h4 class="font-semibold text-gray-800 dark:text-white">Detail Surat Masuk</h4>
                                        <p class="text-xs text-gray-400 mt-0.5">No Agenda: {{ $d->suratMasuk->no_agenda ?? '-' }}</p>
                                    </div>
                                    <button onclick="document.getElementById('dispo-modal-{{ $d->id }}').classList.add('hidden')"
                                            class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 hover:text-gray-600 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="flex-1 overflow-y-auto px-6 py-5">
                                    <div class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Nomor Surat</p>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $d->suratMasuk->nomor_surat ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Pengirim</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $d->suratMasuk->pengirim ?? '-' }}</p>
                                        </div>
                                        <div class="col-span-2">
                                            <p class="text-xs text-gray-400 mb-0.5">Perihal</p>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $d->suratMasuk->judul_surat ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Tanggal Surat</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $d->suratMasuk->tanggal_surat?->format('d/m/Y') ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Tanggal Arsip</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $d->suratMasuk->tanggal_arsip?->format('d/m/Y') ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Sifat</p>
                                            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $sifatColorModal }}">{{ ucfirst($d->suratMasuk->sifat ?? '-') }}</span>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Kategori</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $d->suratMasuk->kategori->nama_kategori ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Diinput Oleh</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $d->suratMasuk->user->name ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-0.5">Dikirim Oleh</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $d->dariUser->name ?? '-' }}</p>
                                        </div>
                                        @if($d->isi_disposisi)
                                        <div class="col-span-2">
                                            <p class="text-xs text-gray-400 mb-0.5">Instruksi Disposisi</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $d->isi_disposisi }}</p>
                                        </div>
                                        @endif
                                        @if($d->suratMasuk->keterangan)
                                        <div class="col-span-2">
                                            <p class="text-xs text-gray-400 mb-0.5">Keterangan</p>
                                            <p class="text-gray-700 dark:text-gray-300">{{ $d->suratMasuk->keterangan }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="px-6 py-4 border-t dark:border-gray-700 flex gap-2 flex-shrink-0">
                                    @if(auth()->user()->role === 'kasubbag')
                                    <a href="{{ route('disposisi.cetak', $d) }}" target="_blank"
                                       class="inline-flex items-center gap-2 text-sm bg-purple-100 text-purple-700 hover:bg-purple-200 px-4 py-2 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                        Cetak Disposisi
                                    </a>
                                    @endif

                                    @if($d->suratMasuk->file_path)
                                    <a href="{{ Storage::url($d->suratMasuk->file_path) }}" target="_blank"
                                       class="inline-flex items-center gap-2 text-sm bg-green-100 text-green-700 hover:bg-green-200 px-4 py-2 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Lihat File
                                    </a>
                                    @endif

                                    <button onclick="document.getElementById('dispo-modal-{{ $d->id }}').classList.add('hidden')"
                                            class="text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white text-gray-700 px-4 py-2 rounded-lg transition ml-auto">
                                        Tutup
                                    </button>
                                </div>

                            </div>
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-10 h-10 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm">Tidak ada disposisi yang perlu diproses</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($disposisis->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
        {{ $disposisis->links() }}
    </div>
    @endif
</div>

{{-- Script: Kasubag auto-selesai saat buka detail --}}
@if(auth()->user()->role === 'kasubbag')
<script>
function bukaDetailKasubag(dispoId) {
    var modal = document.getElementById('dispo-modal-' + dispoId);
    var badge = document.getElementById('status-badge-' + dispoId);
    var row   = document.getElementById('row-' + dispoId);

    // Buka modal
    modal.classList.remove('hidden');

    // Jika belum selesai, kirim AJAX untuk tandai selesai
    if (badge && badge.textContent.trim() !== 'Selesai') {
        fetch('/disposisi/' + dispoId + '/tandai-selesai', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                // Update badge di tabel
                badge.textContent = 'Selesai';
                badge.className = 'text-xs px-2.5 py-1 rounded-full font-medium bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400';

                // Hapus highlight biru
                if (row) {
                    row.classList.remove('bg-blue-50/30', 'dark:bg-blue-900/10');
                }
            }
        })
        .catch(function(err) {
            console.warn('Tandai selesai error:', err);
        });
    }
}
</script>
@endif

@endsection