<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Master Skill (Contoh: Laravel, React, Las Listrik, Akuntansi)
        Schema::create('master_skills', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_skill')->unique();
            $table->timestamps();
        });

        // 2. Master Instansi (Contoh: Universitas Dehasen, UNIB, SMKN 1 Kota Bengkulu)
        Schema::create('master_instansis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_instansi')->unique();
            $table->string('jenis_instansi'); // SMA, SMK, Universitas, Kursus
            $table->timestamps();
        });

        // 3. Master Perusahaan (Contoh: PT. Aranus, Bank Bengkulu, Telkom)
        Schema::create('master_perusahaans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_perusahaan')->unique();
            $table->string('bidang_usaha')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_perusahaans');
        Schema::dropIfExists('master_instansis');
        Schema::dropIfExists('master_skills');
    }
};