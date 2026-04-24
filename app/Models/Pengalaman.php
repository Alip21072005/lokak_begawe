<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengalaman extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pengalamans';
    protected $guarded = [];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'is_current' => 'boolean',
    ];

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id', 'pelamar_id');
    }

    // Relasi ke Master Data Perusahaan
    public function masterPerusahaan()
    {
        return $this->belongsTo(MasterPerusahaan::class, 'master_perusahaan_id');
    }
}