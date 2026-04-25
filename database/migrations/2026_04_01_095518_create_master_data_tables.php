<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database/migrations/2026_04_01_095518_create_master_data_tables.php

    public function up(): void
    {
        Schema::create('master_skills', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_skill')->unique();
            $table->timestamps();
        });

        Schema::create('master_instansis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_instansi')->unique();
            $table->string('jenis_instansi');
            $table->timestamps();
        });

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