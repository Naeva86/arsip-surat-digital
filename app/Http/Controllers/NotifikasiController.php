<?php
// app/Http/Controllers/NotifikasiController.php

namespace App\Http\Controllers;

use App\Models\Disposisi;

class NotifikasiController extends Controller
{
    public function data()
    {
        $disposisis = Disposisi::with(['suratMasuk', 'dariUser'])
            ->where('kepada_user_id', auth()->id())
            ->whereIn('status', ['menunggu', 'dibaca'])
            ->whereHas('suratMasuk')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($d) => [
                'id'      => $d->id,
                'perihal' => $d->suratMasuk->judul_surat ?? '-',
                'dari'    => $d->dariUser->name ?? '-',
                'waktu'   => $d->created_at->diffForHumans(),
                'url'     => route('disposisi.show', $d->id),
            ]);

        return response()->json([
            'count' => $disposisis->count(),
            'items' => $disposisis->values(),
        ]);
    }

    public function riwayat()
    {
        $semua = Disposisi::with(['suratMasuk', 'dariUser'])
            ->where('kepada_user_id', auth()->id())
            ->whereHas('suratMasuk')
            ->latest()
            ->take(50)
            ->get()
            ->map(fn($d) => [
                'id'          => $d->id,
                'perihal'     => $d->suratMasuk->judul_surat ?? '-',
                'dari'        => $d->dariUser->name ?? '-',
                'waktu'       => $d->created_at->diffForHumans(),
                'waktu_full'  => $d->created_at->format('d/m/Y H:i'),
                'status'      => $d->status,
                'url'         => route('disposisi.show', $d->id),
                'no_surat'    => $d->suratMasuk->nomor_surat ?? '-',
                'isi'         => $d->isi_disposisi,
                'dibaca_at'   => $d->dibaca_at?->format('d/m/Y H:i'),
                'selesai_at'  => $d->selesai_at?->format('d/m/Y H:i'),
            ]);

        return response()->json([
            'belum_dibaca' => $semua->where('status', 'menunggu')->values(),
            'diproses'     => $semua->whereIn('status', ['dibaca', 'diproses'])->values(),
            'selesai'      => $semua->where('status', 'selesai')->values(),
        ]);
    }
}