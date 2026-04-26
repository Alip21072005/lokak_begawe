<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pendidikans';
    protected $guarded = [];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_lulus' => 'date',
    ];

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id', 'pelamar_id');
    }

    // Relasi ke Master Data Instansi (Kampus/Sekolah)
    public function masterInstansi()
    {
        return $this->belongsTo(MasterInstansi::class, 'master_instansi_id');
    }
}