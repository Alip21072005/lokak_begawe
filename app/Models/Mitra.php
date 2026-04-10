<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mitra extends Model
{
    use UUID;



    protected $table = 'mitra';
    protected $primaryKey = 'mitra_id';

    protected $fillable = [
        'user_id',
        'lokasi_id',
        'kategori_id',
        'nama_mitra',
        'deskripsi_mitra',
        'website_mitra',
        'logo_mitra',
        'banner_mitra',

        'email_mitra',
        'alamat_mitra',
        'nohp_mitra',
        'dokumen_mitra',
        'status_mitra'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }


    public function lowongan(): HasMany
    {
        return $this->hasMany(Lowongan::class, 'mitra_id');
    }


    public function rating(): HasMany
    {
        return $this->hasMany(Rating::class, 'mitra_id');
    }
}