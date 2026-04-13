<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelamars', function (Blueprint $table) {
            // Gunakan pelamar_id sebagai primary key UUID
            $table->uuid('pelamar_id')->primary();

            // Relasi ke tabel Users
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');

            // Relasi ke tabel master lainnya (Cukup satu baris per relasi)
            $table->foreignUuid('lokasi_id')->constrained('lokasis')->onDelete('cascade');
            $table->foreignUuid('pendidikan_id')->constrained('pendidikans')->onDelete('cascade');
            $table->foreignUuid('pengalaman_id')->constrained('pengalamans')->onDelete('cascade');
            $table->foreignUuid('lamaran_id')->constrained('lamarans')->onDelete('cascade');
            $table->foreignUuid('skill_id')->constrained('skills')->onDelete('cascade');

            $table->string('nama_pelamar');
            $table->string('email_pelamar');
            $table->string('nohp_pelamar');
            $table->text('alamat_pelamar');
            $table->string('jenis_kelamin');
            $table->string('cv_pelamar')->nullable();
            $table->string('foto_pelamar')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelamars');
    }
};