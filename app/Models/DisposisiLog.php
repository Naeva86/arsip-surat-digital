<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisposisiLog extends Model
{
    protected $fillable = [
        'disposisi_id', 'user_id',
        'status_lama', 'status_baru',
        'catatan', 'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function disposisi()
    {
        return $this->belongsTo(Disposisi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}