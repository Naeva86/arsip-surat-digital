<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    protected $fillable = ['nama_bagian', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Bagian::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Bagian::class, 'parent_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}