<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'transaksis';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'lowongan_id',
        'nama_paket',
        'harga',
        'bukti_transfer',
        'status_pembayaran',
    ];

    /**
     * Relasi Balik ke Lowongan
     * Setiap transaksi mereferensikan satu lowongan tertentu
     */
    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }

    /**
     * Scope untuk mempermudah filter transaksi yang belum diverifikasi
     */
    public function scopePending($query)
    {
        return $query->where('status_pembayaran', 'pending');
    }
}