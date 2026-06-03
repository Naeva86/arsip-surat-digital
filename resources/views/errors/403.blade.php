<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>403 — Akses Ditolak</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="text-8xl font-bold text-blue-200 mb-4">403</div>
        <h1 class="text-2xl font-bold text-gray-700 mb-2">Akses Ditolak</h1>
        <p class="text-gray-500 mb-6">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ route('dashboard') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm">
            Kembali ke Dashboard
        </a>
    </div>
</body>
</html>