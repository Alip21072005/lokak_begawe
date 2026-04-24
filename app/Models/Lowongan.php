<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lowongan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'lowongans';
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

        'minimal_pendidikan', // Contoh: SMK, S1, D3
        'minimal_pengalaman', // Dalam satuan tahun (integer)
    ];

    /**
     * Relasi ke Mitra (Pemilik Lowongan)
     */
    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }

    /**
     * Relasi ke Lokasi Penempatan
     */
    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(MasterSkill::class, 'lowongan_skills', 'lowongan_id', 'master_skill_id')
            ->withPivot('id')
            ->withTimestamps();
    }


    public function lamaran(): HasMany
    {
        return $this->hasMany(Lamaran::class, 'lowongan_id');
    }


    public function transaksi(): HasOne
    {
        return $this->hasOne(Transaksi::class, 'lowongan_id');
    }
}
