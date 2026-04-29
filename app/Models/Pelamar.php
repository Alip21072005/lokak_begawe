<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelamar extends Model
{
    use HasFactory, HasUuids;

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
        'bio',
        'website_portfolio',
        'cv_pelamar',
        'foto_pelamar',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class, 'pelamar_id', 'pelamar_id');
    }

    public function pengalamans(): HasMany
    {
        return $this->hasMany(Pengalaman::class, 'pelamar_id', 'pelamar_id')->orderBy('tgl_mulai', 'desc');
    }

    public function pendidikans(): HasMany
    {
        return $this->hasMany(Pendidikan::class, 'pelamar_id', 'pelamar_id')->orderBy('tgl_mulai', 'desc');
    }
    public function lamaran(): HasMany
    {
        // Pastikan kamu punya Model bernama 'Lamaran'
        return $this->hasMany(Lamaran::class, 'pelamar_id', 'pelamar_id');
    }
}
