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


    protected $table = 'pelamar';
    protected $primaryKey = 'pelamar_id';

    protected $fillable = [
        'user_id',
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


    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'pelamar_skill', 'pelamar_id', 'skill_id');
    }


    public function lamaran(): HasMany
    {
        return $this->hasMany(Lamaran::class, 'pelamar_id');
    }


    public function rating(): HasMany
    {
        return $this->hasMany(Rating::class, 'pelamar_id');
    }
}