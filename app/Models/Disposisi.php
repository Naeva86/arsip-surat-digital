<?php
// app/Models/Disposisi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'dibaca_at'    => 'datetime',
        'diproses_at'  => 'datetime',
        'selesai_at'   => 'datetime',
    ];

    public function suratMasuk()   { return $this->belongsTo(SuratMasuk::class); }
    public function dariUser()     { return $this->belongsTo(User::class, 'dari_user_id'); }
    public function kepadaUser()   { return $this->belongsTo(User::class, 'kepada_user_id'); }
    public function tujuanBagian() { return $this->belongsTo(Bagian::class, 'tujuan_bagian_id'); }
    public function logs()         { return $this->hasMany(DisposisiLog::class); }
}