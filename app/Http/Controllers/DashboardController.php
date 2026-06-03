<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Disposisi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ══════════════════════════════════════
        // DATA UNTUK SEMUA ROLE
        // ══════════════════════════════════════
        $totalSuratMasuk    = SuratMasuk::count();
        $totalSuratKeluar   = SuratKeluar::count();
        $suratMasukBulanIni = SuratMasuk::whereMonth('tanggal_arsip', now()->month)->whereYear('tanggal_arsip', now()->year)->count();
        $suratKeluarBulanIni= SuratKeluar::whereMonth('tanggal_arsip', now()->month)->whereYear('tanggal_arsip', now()->year)->count();
        $prosesDisposisi    = SuratMasuk::whereIn('status', ['menunggu_direktur', 'proses_disposisi'])->count();

        $disposisiSaya = Disposisi::where('kepada_user_id', $user->id)
            ->where('status', 'menunggu')
            ->whereHas('suratMasuk')
            ->count();

        // ══════════════════════════════════════
        // ADMIN & STAFF: Chart + Tabel
        // ══════════════════════════════════════
        $bulanList  = [];
        $dataMasuk  = [];
        $dataKeluar = [];
        $statusData = [0, 0, 0, 0, 0];
        $suratMasukTerbaru = collect();

        if (in_array($user->role, ['admin', 'staff'])) {
            // Tren 6 bulan
            for ($i = 5; $i >= 0; $i--) {
                $bulan = Carbon::now()->subMonths($i);
                $bulanList[] = $bulan->translatedFormat('M Y');
                $dataMasuk[] = SuratMasuk::whereMonth('tanggal_arsip', $bulan->month)
                    ->whereYear('tanggal_arsip', $bulan->year)->count();
                $dataKeluar[] = SuratKeluar::whereMonth('tanggal_arsip', $bulan->month)
                    ->whereYear('tanggal_arsip', $bulan->year)->count();
            }

            // Status donut — update untuk status baru
            $statusData = [
                SuratMasuk::where('status', 'menunggu_direktur')->count(),
                SuratMasuk::where('status', 'proses_disposisi')->count(),
                SuratMasuk::where('status', 'ditolak')->count(),
                SuratMasuk::where('status', 'selesai')->count(),
            ];

            // Surat terbaru
            $suratMasukTerbaru = SuratMasuk::with(['kategori', 'user'])
                ->latest('tanggal_arsip')->take(5)->get();
        }

        // ══════════════════════════════════════
        // DIREKTUR, KABAG, KASUBAG: Prioritas
        // ══════════════════════════════════════
        $disposisiMenunggu = collect();
        $suratPentings     = collect();
        $countPenting = $countUrgent = $countRahasia = 0;

        if (in_array($user->role, ['direktur', 'kabag', 'kasubbag'])) {
            $disposisiMenunggu = Disposisi::with(['suratMasuk.kategori', 'dariUser'])
                ->where('kepada_user_id', $user->id)
                ->where('status', 'menunggu')
                ->whereHas('suratMasuk')
                ->latest()->take(5)->get();

            $suratPentings = SuratMasuk::with(['kategori'])
                ->whereIn('sifat', ['penting', 'urgent', 'rahasia'])
                ->where('status', '!=', 'selesai')
                ->latest('tanggal_arsip')->take(5)->get();

            $countPenting = SuratMasuk::where('sifat', 'penting')->where('status', '!=', 'selesai')->count();
            $countUrgent  = SuratMasuk::where('sifat', 'urgent')->where('status', '!=', 'selesai')->count();
            $countRahasia = SuratMasuk::where('sifat', 'rahasia')->where('status', '!=', 'selesai')->count();
        }

        return view('dashboard', compact(
            'totalSuratMasuk', 'totalSuratKeluar', 'suratMasukBulanIni', 'suratKeluarBulanIni',
            'prosesDisposisi', 'disposisiSaya',
            'bulanList', 'dataMasuk', 'dataKeluar', 'statusData', 'suratMasukTerbaru',
            'disposisiMenunggu', 'suratPentings', 'countPenting', 'countUrgent', 'countRahasia'
        ));
    }
}