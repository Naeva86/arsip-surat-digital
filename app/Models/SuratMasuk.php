<?php
// app/Models/SuratMasuk.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratMasuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_arsip'  => 'date',
    ];

    public static function generateNoAgenda(): string
    {
        $bulan = now()->format('m');
        $tahun = now()->format('Y');
        $prefix = "SM/{$bulan}/{$tahun}/";

        // Cari termasuk yang soft-deleted supaya tidak duplikat
        $last = static::withTrashed()
                    ->where('no_agenda', 'like', $prefix . '%')
                    ->orderByRaw('CAST(SUBSTRING(no_agenda, -4) AS UNSIGNED) DESC')
                    ->first();

        $urut = $last ? (int) substr($last->no_agenda, -4) + 1 : 1;

        return $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'baru'               => 'Baru',
            'menunggu_direktur'  => 'Menunggu Direktur',
            'ditolak'            => 'Ditolak',
            'proses_disposisi'   => 'Proses Disposisi',
            'selesai'            => 'Selesai',
            default              => ucfirst($this->status),
        };
    }

    public function user()      { return $this->belongsTo(User::class); }
    public function kategori()  { return $this->belongsTo(KategoriSurat::class, 'kategori_id'); }
    public function disposisis() { return $this->hasMany(Disposisi::class, 'surat_masuk_id'); }

    // Disposisi terbaru (aktif)
    public function disposisiAktif()
    {
        return $this->hasOne(Disposisi::class, 'surat_masuk_id')->latestOfMany();
    }
}