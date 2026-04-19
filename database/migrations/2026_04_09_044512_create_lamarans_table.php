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
            $table->uuid('id')->primary(); // ID Lamaran itu sendiri

            // KOLOM WAJIB: Ini yang dicari Laravel tadi
            $table->foreignUuid('pelamar_id')->constrained('pelamars', 'pelamar_id')->onDelete('cascade');

            // Relasi ke lowongan
            $table->foreignUuid('lowongan_id')->constrained('lowongans')->onDelete('cascade');

            $table->string('status')->default('pending'); // pending, accepted, rejected
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