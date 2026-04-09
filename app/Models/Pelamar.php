<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Pelamar extends Model
{
    use UUID;

    protected $fillable = [
        'user_id',
        'nama_pelamar',
        'email_pelamar',
        'nohp_pelamar',
        'alamat_pelamar',
        'cv_pelamar',
        'foto_pelamar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lokasi()
    {
        return $this->hasOne(Lokasi::class);
    }
}