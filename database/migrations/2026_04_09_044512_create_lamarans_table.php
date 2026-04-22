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
        Schema::create('lamarans', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relasi ke Pelamar & Lowongan
            $table->foreignUuid('pelamar_id')->constrained('pelamars', 'pelamar_id')->onDelete('cascade');
            $table->foreignUuid('lowongan_id')->constrained('lowongans')->onDelete('cascade');

            // Data Lamaran
            $table->text('catatan')->nullable(); // Pesan dari pelamar saat melamar
            $table->enum('status', [
                'pending',
                'reviewed',
                'interview',
                'accepted',
                'rejected'
            ])->default('pending');

            $table->text('catatan_mitra')->nullable(); // Pesan feedback/alasan dari Mitra

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lamarans');
    }
};