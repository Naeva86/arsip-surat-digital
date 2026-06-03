<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\Master\BagianController;
use App\Http\Controllers\Master\JabatanController;
use App\Http\Controllers\Master\KategoriSuratController;
use App\Http\Controllers\Master\UserController;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Surat Masuk — Staff & Admin
    Route::resource('surat-masuk', SuratMasukController::class);

    // Surat Keluar — Staff & Admin
    Route::resource('surat-keluar', SuratKeluarController::class);

    // Disposisi — Direktur, Kabag, Kasubag
    Route::prefix('disposisi')->name('disposisi.')->group(function () {
        Route::get('/',                        [DisposisiController::class, 'index'])->name('index');
        Route::get('/{disposisi}',             [DisposisiController::class, 'show'])->name('show');
        Route::post('/{disposisi}/proses',     [DisposisiController::class, 'proses'])->name('proses');
        Route::get('/{disposisi}/cetak',       [DisposisiController::class, 'cetak'])->name('cetak');
        Route::get('/tracking/{suratMasuk}',   [DisposisiController::class, 'tracking'])->name('tracking');
    });

    // Lemari Arsip Surat — Semua role
    Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');

    // Laporan — Staff, Direktur, Admin
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/',             [LaporanController::class, 'index'])->name('index');
        Route::get('/export-pdf',   [LaporanController::class, 'exportPdf'])->name('pdf');
        Route::get('/export-excel', [LaporanController::class, 'exportExcel'])->name('excel');
    });

    // Notifikasi
    Route::get('/notifikasi/data',    [NotifikasiController::class, 'data'])->name('notifikasi.data');
    Route::get('/notifikasi/riwayat', [NotifikasiController::class, 'riwayat'])->name('notifikasi.riwayat');

    // Profil
    Route::get('/profil',   [ProfilController::class, 'edit'])->name('profil.edit');
    Route::patch('/profil', [ProfilController::class, 'update'])->name('profil.update');

    // Master Data — Admin only
    Route::middleware(['role:admin'])->prefix('master')->name('master.')->group(function () {
        Route::resource('bagian',   BagianController::class);
        Route::resource('jabatan',  JabatanController::class);
        Route::resource('kategori', KategoriSuratController::class);
        Route::resource('user',     UserController::class);
    });
    Route::prefix('disposisi')->name('disposisi.')->group(function () {
    Route::get('/',                        [DisposisiController::class, 'index'])->name('index');
    Route::get('/{disposisi}',             [DisposisiController::class, 'show'])->name('show');
    Route::post('/{disposisi}/proses',     [DisposisiController::class, 'proses'])->name('proses');
    Route::post('/{disposisi}/tandai-selesai', [DisposisiController::class, 'tandaiSelesai'])->name('tandai-selesai');
    Route::get('/{disposisi}/cetak',       [DisposisiController::class, 'cetak'])->name('cetak');
    Route::get('/tracking/{suratMasuk}',   [DisposisiController::class, 'tracking'])->name('tracking');
});
Route::get('/verifikasi/disposisi/{id}', function ($id, \Illuminate\Http\Request $request) {
    $disposisi = \App\Models\Disposisi::with(['suratMasuk', 'dariUser.jabatan', 'kepadaUser.jabatan'])->find($id);

    if (!$disposisi) {
        return response('<h2>❌ Disposisi tidak ditemukan</h2>', 404);
    }

    $expectedHash = md5($disposisi->id . $disposisi->suratMasuk->no_agenda . $disposisi->created_at);

    if ($request->hash !== $expectedHash) {
        return response('<h2>❌ Hash tidak valid — dokumen mungkin dipalsukan</h2>', 403);
    }

    return response()->view('verifikasi.disposisi', compact('disposisi'));
})->name('verifikasi.disposisi');
});

require __DIR__ . '/auth.php';