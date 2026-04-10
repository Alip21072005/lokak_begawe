<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Mitra extends Model
{
    use UUID;

    protected $fillable = [
        'user_id',
        'lokasi_id',
        'kategori_id',
        'nama_mitra',
        'logo_mitra',
        'banner_mitra',
        'email_mitra',
        'website_mitra',
        'deksipsi_mitra',
        'alamat_mitra',
        'nohp_mitra',
        'dokumen_mitra',
        'status_mitra'
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