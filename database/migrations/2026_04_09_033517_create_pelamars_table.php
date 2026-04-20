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

            // HANYA INI YANG BENAR: Relasi ke tabel Induk (User) dan Lokasi utama
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('lokasi_id')->constrained('lokasis')->onDelete('cascade');


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