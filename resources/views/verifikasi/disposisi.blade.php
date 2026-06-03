<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Disposisi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden">
        <div class="bg-green-600 px-6 py-5 text-center">
            <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-lg font-bold text-white">Dokumen Terverifikasi</h1>
            <p class="text-green-100 text-sm mt-1">Tanda tangan digital valid</p>
        </div>

        <div class="p-6 space-y-4 text-sm">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">No Agenda</p>
                    <p class="font-mono font-medium text-gray-800">{{ $disposisi->suratMasuk->no_agenda }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Nomor Surat</p>
                    <p class="font-medium text-gray-800">{{ $disposisi->suratMasuk->nomor_surat }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-400 mb-0.5">Perihal</p>
                    <p class="font-medium text-gray-800">{{ $disposisi->suratMasuk->judul_surat }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Yang Mendisposisi</p>
                    <p class="font-medium text-gray-800">{{ $disposisi->dariUser->name ?? '-' }}</p>
                    <p class="text-xs text-gray-500">{{ $disposisi->dariUser->jabatan->nama_jabatan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Yang Menerima</p>
                    <p class="font-medium text-gray-800">{{ $disposisi->kepadaUser->name ?? '-' }}</p>
                    <p class="text-xs text-gray-500">{{ $disposisi->kepadaUser->jabatan->nama_jabatan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Tanggal Disposisi</p>
                    <p class="text-gray-700">{{ $disposisi->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Status</p>
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-green-100 text-green-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        {{ ucfirst($disposisi->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t text-center">
            <p class="text-xs text-gray-400">PERUMDA Air Minum Tirta Danu Arta — Kabupaten Bangli</p>
            <p class="text-xs text-gray-300 mt-1">Sistem Arsip Digital</p>
        </div>
    </div>
</body>
</html>