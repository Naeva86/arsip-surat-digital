<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriSurat extends Model
{
    protected $fillable = ['nama_kategori'];

    public function suratMasuks()
    {
        return $this->hasMany(SuratMasuk::class, 'kategori_id');
    }

    public function suratKeluars()
    {
        return $this->hasMany(SuratKeluar::class, 'kategori_id');
    }
}