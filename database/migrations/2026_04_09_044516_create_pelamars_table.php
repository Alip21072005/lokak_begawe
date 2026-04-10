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
            $table->uuid('pelamar_id')->primary();
            $table->uuid('user_id');
            $table->uuid('lokasi_id');
            $table->uuid('pendidikan_id');
            $table->uuid('pengalaman_id');
            $table->uuid('lamaran_id');
            $table->uuid('skill_id');
            $table->string('nama_pelamar');
            $table->string('email_pelamar');
            $table->string('nohp_pelamar');
            $table->text('alamat_pelamar');
            $table->string('jenis_kelamin');
            $table->string('cv_pelamar')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lokasi_id')->references('id')->on('lokasi')->onDelete('cascade');
            $table->foreign('pendidikan_id')->references('id')->on('pendidikans')->onDelete('cascade');
            $table->foreign('pengalaman_id')->references('id')->on('pengalamans')->onDelete('cascade');
            $table->foreign('lamaran_id')->references('id')->on('lamarans')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
            $table->string('foto_pelamar')->nullable();
            $table->string('cv_pelamar')->nullable();
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