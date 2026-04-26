<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSkill extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    /**
     * Relasi ke detail skill pelamar
     */
    public function pelamarSkills()
    {
        return $this->hasMany(Skill::class, 'master_skill_id');
    }
}