<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'kategoris';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Relasi ke Tabel Mitra
     * Satu kategori bisa dimiliki oleh banyak mitra (One-to-Many)
     */
    public function mitra(): HasMany
    {
        // Pastikan 'kategori_id' adalah nama kolom foreign key di tabel mitras
        return $this->hasMany(Mitra::class, 'kategori_id');
    }
}