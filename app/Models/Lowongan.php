<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lowongan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'lowongans';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    // CEK AREA INI (Baris 19-29 biasanya di sini)
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
    ]; // Pastikan ada tutup kurung kotak dan titik koma di sini!

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function lamaran(): HasMany
    {
        return $this->hasMany(Lamaran::class, 'lowongan_id');
    }

    public function transaksi(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Transaksi::class, 'lowongan_id');
    }
}