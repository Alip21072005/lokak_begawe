<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterInstansi extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    /**
     * Relasi ke riwayat pendidikan pelamar
     */
    public function pelamarPendidikans()
    {
        return $this->hasMany(Pendidikan::class, 'master_instansi_id');
    }
}