<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelamar extends Model
{
    use UUID;


    protected $table = 'pelamars';
    protected $primaryKey = 'pelamar_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'user_id',
        'pelamar_id',
        'lokasi_id',
        'nama_pelamar',
        'email_pelamar',
        'nohp_pelamar',
        'alamat_pelamar',
        'jenis_kelamin',
        'cv_pelamar',
        'foto_pelamar',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function pendidikan(): HasMany
    {
        return $this->hasMany(Pendidikan::class, 'pelamar_id');
    }


    public function pengalaman(): HasMany
    {
        return $this->hasMany(Pengalaman::class, 'pelamar_id');
    }



    public function lamaran(): HasMany
    {
        return $this->hasMany(Lamaran::class, 'pelamar_id');
    }


    public function rating(): HasMany
    {
        return $this->hasMany(Rating::class, 'pelamar_id');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }
    public function skills()
    {
        return $this->hasMany(Skill::class, 'pelamar_id', 'pelamar_id');
    }

    public function pengalamans()
    {
        return $this->hasMany(Pengalaman::class, 'pelamar_id', 'pelamar_id')->orderBy('tgl_mulai', 'desc');
    }

    public function pendidikans()
    {
        return $this->hasMany(Pendidikan::class, 'pelamar_id', 'pelamar_id')->orderBy('tgl_mulai', 'desc');
    }
    public function masterSkill()
    {
        return $this->belongsTo(MasterSkill::class, 'master_skill_id');
    }
    public function masterInstansi()
    {
        return $this->belongsTo(MasterInstansi::class, 'master_instansi_id');
    }
    public function masterPerusahaan()
    {
        return $this->belongsTo(MasterPerusahaan::class, 'master_perusahaan_id');
    }
}
