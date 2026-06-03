<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratKeluar extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nomor_surat', 'judul_surat', 'penerima',
        'tanggal_surat', 'tanggal_arsip', 'sifat',
        'kategori_id', 'file_path', 'keterangan',
        'user_id', 'bagian_id',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_arsip' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriSurat::class, 'kategori_id');
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }
}