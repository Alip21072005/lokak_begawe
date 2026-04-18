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

            // Gunakan foreignUuid agar sinkron dengan ID tabel induk yang juga UUID
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('lokasi_id')->constrained('lokasis')->onDelete('cascade');
            $table->foreignUuid('kategori_id')->constrained('kategoris')->onDelete('cascade');
            $table->enum('status_mitra', ['pending', 'verified', 'rejected'])->default('pending');
            $table->string('nama_mitra');
            $table->string('logo_mitra')->nullable();
            $table->string('banner_mitra')->nullable();
            $table->string('email_mitra');
            $table->string('website_mitra')->nullable();
            $table->text('deskripsi_mitra');
            $table->text('alamat_mitra');
            $table->string('nohp_mitra');
            $table->string('dokumen_mitra')->nullable();


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