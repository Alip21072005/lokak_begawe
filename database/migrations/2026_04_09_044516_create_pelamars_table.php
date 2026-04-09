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
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('lokasi_id');
            $table->string('nama_pelamar');
            $table->string('email_pelamar');
            $table->string('nohp_pelamar');
            $table->text('alamat_pelamar');
            $table->string('dokumen_pelamar')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lokasi_id')->references('id')->on('lokasi')->onDelete('cascade');
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