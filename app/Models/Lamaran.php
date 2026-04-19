<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lamaran extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'pelamar_id',
        'lowongan_id',
        'status',
    ];

    /**
     * Relasi ke Lowongan (Penyebab Error Tadi)
     */
    public function lowongan(): BelongsTo
    {
        // Lamaran ini milik sebuah Lowongan
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }

    /**
     * Relasi ke Pelamar
     */
    public function pelamar(): BelongsTo
    {
        // Lamaran ini dilakukan oleh seorang Pelamar
        return $this->belongsTo(Pelamar::class, 'pelamar_id');
    }
}