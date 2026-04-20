<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lowongan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'lowongans'; // Harus jamak sesuai migrasi
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'mitra_id',
        'lokasi_id',
        'judul_lowongan',
        'deskripsi_lowongan',
        'tipe_pekerjaan',
        'gaji_min',
        'gaji_max',
        'status_lowongan',
        'tanggal_expired',
    ];

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function lamaran(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Lamaran::class, 'lowongan_id');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }
}