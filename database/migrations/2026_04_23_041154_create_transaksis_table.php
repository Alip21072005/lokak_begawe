<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('lowongan_id')->constrained('lowongans')->onDelete('cascade');
            $table->string('nama_paket'); // Contoh: Basic, Pro, Gold
            $table->integer('harga');
            $table->string('bukti_transfer');
            $table->enum('status_pembayaran', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};