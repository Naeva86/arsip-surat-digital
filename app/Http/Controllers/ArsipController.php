<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'masuk');
        $search = $request->get('search');

        $queryMasuk = SuratMasuk::with(['kategori', 'user', 'disposisis.dariUser', 'disposisis.kepadaUser'])
            ->where('status', 'selesai')
            ->latest('tanggal_arsip');

        if ($search) {
            $queryMasuk->where(function ($q) use ($search) {
                $q->where('no_agenda', 'like', "%$search%")
                ->orWhere('nomor_surat', 'like', "%$search%")
                ->orWhere('judul_surat', 'like', "%$search%")
                ->orWhere('pengirim', 'like', "%$search%");
            });
        }

        $suratMasuks = $queryMasuk->paginate(10, ['*'], 'page_masuk')->withQueryString();

        $queryKeluar = SuratKeluar::with(['kategori', 'user', 'bagian'])
            ->latest('tanggal_arsip');

        if ($search) {
            $queryKeluar->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%$search%")
                ->orWhere('judul_surat', 'like', "%$search%")
                ->orWhere('penerima', 'like', "%$search%");
            });
        }

        $suratKeluars = $queryKeluar->paginate(10, ['*'], 'page_keluar')->withQueryString();

        return view('arsip.index', compact('suratMasuks', 'suratKeluars', 'tab'));
    }
}