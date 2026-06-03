@extends('layouts.app')
@section('title', 'Proses Disposisi')

@section('content')

@php $surat = $disposisi->suratMasuk; $user = auth()->user(); @endphp

<div class="max-w-4xl mx-auto pb-8">

    {{-- Breadcrumb --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
            <a href="{{ route('disposisi.index') }}" class="hover:text-blue-600 transition">Kotak Disposisi</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 dark:text-gray-300">Proses</span>
        </div>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            {{ $user->role === 'kasubbag' ? 'Detail Disposisi' : 'Proses Disposisi' }}
        </h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Kolom Kiri: Detail Surat + Riwayat --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Info Surat --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Detail Surat</h3>
                        <p class="text-xs text-gray-400">{{ $surat->no_agenda }}</p>
                    </div>
                    @if($surat->file_path)
                    <a href="{{ Storage::url($surat->file_path) }}" target="_blank"
                       class="ml-auto inline-flex items-center gap-1.5 text-xs font-medium bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1.5 rounded-lg transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Lihat File
                    </a>
                    @endif
                </div>
                <div class="p-6 grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Nomor Surat</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $surat->nomor_surat }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Pengirim</p>
                        <p class="text-gray-700 dark:text-gray-300">{{ $surat->pengirim }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-gray-400 mb-0.5">Perihal</p>
                        <p class="font-medium text-gray-800 dark:text-white">{{ $surat->judul_surat }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Tanggal Surat</p>
                        <p class="text-gray-700 dark:text-gray-300">{{ $surat->tanggal_surat->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Sifat</p>
                        @php
                            $sc = match($surat->sifat) { 'urgent' => 'bg-red-50 text-red-600', 'penting' => 'bg-yellow-50 text-yellow-600', 'rahasia' => 'bg-purple-50 text-purple-600', default => 'bg-gray-100 text-gray-500' };
                        @endphp
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $sc }}">{{ ucfirst($surat->sifat) }}</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Kategori</p>
                        <p class="text-gray-700 dark:text-gray-300">{{ $surat->kategori->nama_kategori ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Diinput Oleh</p>
                        <p class="text-gray-700 dark:text-gray-300">{{ $surat->user->name ?? '-' }}</p>
                    </div>
                    @if($surat->keterangan)
                    <div class="col-span-2">
                        <p class="text-xs text-gray-400 mb-0.5">Keterangan</p>
                        <p class="text-gray-700 dark:text-gray-300">{{ $surat->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Riwayat Disposisi --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Riwayat Disposisi</h3>
                </div>
                <div class="p-6">
                    <div class="relative pl-6 space-y-6">
                        <div class="absolute left-[9px] top-2 bottom-2 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                        @foreach($surat->disposisis->sortBy('created_at') as $d)
                        <div class="relative">
                            <div class="absolute -left-6 top-1 w-4 h-4 rounded-full border-2 flex-shrink-0
                                {{ $d->keputusan === 'ditolak' ? 'bg-red-500 border-red-500' : ($d->status === 'selesai' ? 'bg-green-500 border-green-500' : ($d->status === 'menunggu' ? 'bg-blue-500 border-blue-500' : 'bg-yellow-500 border-yellow-500')) }}"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white">
                                    {{ $d->dariUser->name ?? '?' }} → {{ $d->kepadaUser->name ?? '?' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $d->isi_disposisi }}</p>
                                @if($d->keputusan === 'ditolak')
                                <p class="text-xs text-red-500 mt-0.5">❌ Ditolak: {{ $d->catatan_penolakan }}</p>
                                @elseif($d->keputusan === 'setuju')
                                <p class="text-xs text-green-600 mt-0.5">✅ Disetujui{{ $d->instruksi_disposisi ? ' — ' . $d->instruksi_disposisi : '' }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">{{ $d->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════ --}}
        {{-- Kolom Kanan: Form per Role               --}}
        {{-- SATU RANTAI @if-@elseif-@endif           --}}
        {{-- ═══════════════════════════════════════ --}}
        <div class="lg:col-span-1">

            @if($user->role === 'kasubbag')
            {{-- ── KASUBAG: Detail selesai + cetak ── --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-4">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Disposisi Selesai</h3>
                        <p class="text-xs text-gray-400">Surat telah diterima dan selesai</p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl px-4 py-3">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-green-700 dark:text-green-400">Disposisi telah diselesaikan</p>
                            <p class="text-xs text-green-600 dark:text-green-500 mt-0.5">{{ $disposisi->selesai_at?->format('d/m/Y H:i') ?? '-' }}</p>
                        </div>
                    </div>

                    @if($disposisi->isi_disposisi)
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">Instruksi</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 rounded-lg px-3 py-2.5">{{ $disposisi->isi_disposisi }}</p>
                    </div>
                    @endif

                    <a href="{{ route('disposisi.cetak', $disposisi) }}" target="_blank"
                       class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak Lembar Disposisi
                    </a>

                    <a href="{{ route('disposisi.index') }}"
                       class="w-full inline-flex items-center justify-center gap-2 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm font-medium px-4 py-2.5 rounded-lg transition hover:bg-gray-50 dark:hover:bg-gray-700">
                        ← Kembali ke Kotak Disposisi
                    </a>
                </div>
            </div>

            @elseif($user->role === 'direktur')
            {{-- ── DIREKTUR: Form Persetujuan ── --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-4">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Form Persetujuan</h3>
                        <p class="text-xs text-gray-400">Tentukan keputusan Anda</p>
                    </div>
                </div>
                <form action="{{ route('disposisi.proses', $disposisi) }}" method="POST"
                      data-konfirmasi data-tipe="simpan"
                      data-judul="Kirim Disposisi?" data-pesan="Pastikan keputusan sudah benar." data-label-ok="Ya, Kirim">
                    @csrf
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Keputusan <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="keputusan-label flex items-center gap-2 px-3 py-3 border border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer transition hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <input type="radio" name="keputusan" value="setuju" class="hidden keputusan-radio" onchange="toggleKeputusan()">
                                    <span class="w-3 h-3 rounded-full bg-green-500 flex-shrink-0"></span>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Setuju</span>
                                </label>
                                <label class="keputusan-label flex items-center gap-2 px-3 py-3 border border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer transition hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <input type="radio" name="keputusan" value="ditolak" class="hidden keputusan-radio" onchange="toggleKeputusan()">
                                    <span class="w-3 h-3 rounded-full bg-red-500 flex-shrink-0"></span>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Tolak</span>
                                </label>
                            </div>
                        </div>

                        <div id="panel-setuju" class="hidden space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Instruksi Disposisi <span class="text-red-500">*</span></label>
                                <select name="instruksi" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                    <option value="">-- Pilih Instruksi --</option>
                                    <option value="Tindak lanjuti">Tindak lanjuti</option>
                                    <option value="Untuk diketahui">Untuk diketahui</option>
                                    <option value="Koordinasikan">Koordinasikan</option>
                                    <option value="Siapkan laporan">Siapkan laporan</option>
                                    <option value="Jawab sesuai prosedur">Jawab sesuai prosedur</option>
                                    <option value="Arsipkan">Arsipkan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Disposisi ke Kabag <span class="text-red-500">*</span></label>
                                <select name="tujuan_bagian_id" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                    <option value="">-- Pilih Bagian --</option>
                                    @foreach($bagians as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama_bagian }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Catatan <span class="text-gray-400 normal-case font-normal">(opsional)</span></label>
                            <textarea name="catatan" rows="3" placeholder="Catatan tambahan..."
                                      class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none"></textarea>
                        </div>

                        <button type="submit" id="btn-kirim" disabled
                                class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Disposisi
                        </button>
                    </div>
                </form>
            </div>

            @elseif($user->role === 'kabag')
            {{-- ── KABAG: Form Disposisi ── --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-4">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Form Disposisi</h3>
                        <p class="text-xs text-gray-400">Tentukan tindakan selanjutnya</p>
                    </div>
                </div>
                <form action="{{ route('disposisi.proses', $disposisi) }}" method="POST"
                    data-konfirmasi data-tipe="simpan"
                    data-judul="Kirim Disposisi?" data-pesan="Pastikan data sudah benar." data-label-ok="Ya, Kirim">
                    @csrf
                    <div class="p-6 space-y-5">

                        {{-- Tindakan --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">Tindakan <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="aksi-label flex items-center gap-2 px-3 py-3 border border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer transition hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <input type="radio" name="aksi_kabag" value="selesai" class="hidden aksi-radio" onchange="toggleAksiKabag()">
                                    <span class="w-3 h-3 rounded-full bg-green-500 flex-shrink-0"></span>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Selesai</span>
                                </label>
                                <label class="aksi-label flex items-center gap-2 px-3 py-3 border border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer transition hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <input type="radio" name="aksi_kabag" value="lanjutkan" class="hidden aksi-radio" onchange="toggleAksiKabag()">
                                    <span class="w-3 h-3 rounded-full bg-blue-500 flex-shrink-0"></span>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Ke Kasubag</span>
                                </label>
                            </div>
                        </div>

                        {{-- Panel Lanjutkan ke Kasubag --}}
                        <div id="panel-kasubag" class="hidden space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Instruksi Disposisi <span class="text-red-500">*</span></label>
                                <select name="instruksi" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                    <option value="">-- Pilih Instruksi --</option>
                                    <option value="Tindak lanjuti">Tindak lanjuti</option>
                                    <option value="Untuk diketahui">Untuk diketahui</option>
                                    <option value="Koordinasikan">Koordinasikan</option>
                                    <option value="Siapkan laporan">Siapkan laporan</option>
                                    <option value="Jawab sesuai prosedur">Jawab sesuai prosedur</option>
                                    <option value="Arsipkan">Arsipkan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Disposisi ke Kasubag <span class="text-red-500">*</span></label>
                                <select name="kepada_kasubag_id" class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                    <option value="">-- Pilih Kasubag --</option>
                                    @foreach($kasubags as $ks)
                                    <option value="{{ $ks->id }}">{{ $ks->name }} — {{ $ks->bagian->nama_bagian ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Catatan <span class="text-gray-400 normal-case font-normal">(opsional)</span></label>
                            <textarea name="catatan" rows="3" placeholder="Catatan tambahan..."
                                    class="w-full border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none"></textarea>
                        </div>

                        <button type="submit" id="btn-kirim-kabag" disabled
                                class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Disposisi
                        </button>
                    </div>
                </form>
            </div>

            @endif
            {{-- ═══ END ROLE CHAIN ═══ --}}

        </div>
    </div>
</div>

<script>
// Direktur: toggle panel setuju/tolak
function toggleKeputusan() {
    var val = document.querySelector('input[name="keputusan"]:checked');
    if (!val) return;

    document.querySelectorAll('.keputusan-label').forEach(function(lbl) {
        lbl.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        lbl.classList.add('border-gray-200', 'dark:border-gray-600');
    });
    val.closest('.keputusan-label').classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
    val.closest('.keputusan-label').classList.remove('border-gray-200', 'dark:border-gray-600');

    var panelSetuju = document.getElementById('panel-setuju');
    var btnKirim    = document.getElementById('btn-kirim');

    if (val.value === 'setuju') {
        panelSetuju.classList.remove('hidden');
    } else {
        panelSetuju.classList.add('hidden');
    }
    btnKirim.disabled = false;
}

// Kabag: toggle panel kasubag
function toggleAksiKabag() {
    var val = document.querySelector('input[name="aksi_kabag"]:checked');
    if (!val) return;

    document.querySelectorAll('.aksi-label').forEach(function(lbl) {
        lbl.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        lbl.classList.add('border-gray-200', 'dark:border-gray-600');
    });
    val.closest('.aksi-label').classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
    val.closest('.aksi-label').classList.remove('border-gray-200', 'dark:border-gray-600');

    var panelKasubag = document.getElementById('panel-kasubag');
    var btnKirim     = document.getElementById('btn-kirim-kabag');

    if (val.value === 'lanjutkan') {
        panelKasubag.classList.remove('hidden');
    } else {
        panelKasubag.classList.add('hidden');
    }
    btnKirim.disabled = false;
}
</script>

@endsection