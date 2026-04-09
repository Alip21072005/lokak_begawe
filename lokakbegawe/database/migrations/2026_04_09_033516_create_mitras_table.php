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
        Schema::create('mitras', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('lokasi_id');
            $table->uuid('kategori_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lokasi_id')->references('id')->on('lokasi')->onDelete('cascade');
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade');
            $table->string('nama_mitra');
            $table->string('logo_mitra')->nullable();
            $table->string('banner_mitra')->nullable();
            $table->string('email_mitra');
            $table->string('website_mitra')->nullable();
            $table->text('deksipsi_mitra');
            $table->text('alamat_mitra');
            $table->string('nohp_mitra');
            $table->string('dokumen_mitra')->nullable();
            $table->boolean('status_mitra')->default(false);



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitras');
    }
};