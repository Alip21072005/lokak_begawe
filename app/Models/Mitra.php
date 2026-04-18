<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mitra extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'lokasi_id',
        'kategori_id',
        'nama_mitra',
        'logo_mitra',
        'banner_mitra',
        'email_mitra',
        'website_mitra',
        'deskripsi_mitra',
        'alamat_mitra',
        'nohp_mitra',
        'dokumen_mitra',
        'status_mitra',
    ];

    /**
     * Relasi ke User (PIC Mitra)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Lokasi (PENTING: Untuk Eager Loading di Dashboard)
     */
    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    /**
     * Relasi ke Kategori (INI YANG TADI KURANG & MENYEBABKAN ERROR)
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi ke Lowongan
     */
    public function lowongan(): HasMany
    {
        return $this->hasMany(Lowongan::class, 'mitra_id');
    }

    /**
     * Relasi ke Rating
     */
    public function rating(): HasMany
    {
        return $this->hasMany(Rating::class, 'mitra_id');
    }
}