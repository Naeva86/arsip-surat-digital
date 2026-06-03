<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Exports\SuratMasukExport;
use App\Exports\SuratKeluarExport;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;                          // ← tambah ini
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $suratMasuks = SuratMasuk::with(['kategori', 'user'])
            ->whereMonth('tanggal_arsip', $bulan)
            ->whereYear('tanggal_arsip', $tahun)
            ->get();

        $suratKeluars = SuratKeluar::with(['kategori', 'bagian', 'user'])
            ->whereMonth('tanggal_arsip', $bulan)
            ->whereYear('tanggal_arsip', $tahun)
            ->get();

        return view('laporan.index', compact(
            'suratMasuks',
            'suratKeluars',
            'bulan',
            'tahun'
        ));
    }

    public function exportPdf(Request $request)
    {
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $suratMasuks = SuratMasuk::with(['kategori', 'user'])
            ->whereMonth('tanggal_arsip', $bulan)
            ->whereYear('tanggal_arsip', $tahun)
            ->get();

        $suratKeluars = SuratKeluar::with(['kategori', 'bagian', 'user'])
            ->whereMonth('tanggal_arsip', $bulan)
            ->whereYear('tanggal_arsip', $tahun)
            ->get();

        $pdf = Pdf::loadView('pdf.laporan', compact(
            'suratMasuks',
            'suratKeluars',
            'bulan',
            'tahun'
        ))->setPaper('a4', 'landscape');

        $namaBulan = DateTime::createFromFormat('!m', $bulan)->format('F');
        $namaFile  = 'laporan-surat-' . $namaBulan . '-' . $tahun . '.pdf';

        return $pdf->download($namaFile);
    }

    public function exportExcel(Request $request)
    {
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);

        $namaBulan = DateTime::createFromFormat('!m', $bulan)->format('F');
        $namaFile  = 'laporan-surat-' . $namaBulan . '-' . $tahun . '.xlsx';

        return Excel::download(new LaporanExport($bulan, $tahun), $namaFile);
    }
}