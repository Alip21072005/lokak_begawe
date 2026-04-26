<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $table = 'skills';
    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id', 'pelamar_id');
    }


    public function masterSkill()
    {
        return $this->belongsTo(MasterSkill::class, 'master_skill_id');
    }
}