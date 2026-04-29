<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory, HasUuids;
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
