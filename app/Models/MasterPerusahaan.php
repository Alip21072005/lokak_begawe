<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPerusahaan extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    /**
     * Relasi ke pengalaman kerja pelamar
     */
    public function pelamarPengalamans()
    {
        return $this->hasMany(Pengalaman::class, 'master_perusahaan_id');
    }
}