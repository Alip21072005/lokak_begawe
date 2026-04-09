<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use UUID;
    protected $fillable = [
        'nama_lokasi',
    ];

    public function mitra()
    {
        return $this->hasMany(Mitra::class);
    }

    public function pelamar()
    {
        return $this->hasMany(Pelamar::class);
    }
}