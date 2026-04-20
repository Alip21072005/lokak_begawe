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
        Schema::create('lowongans', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relasi ke Mitra & Lokasi
            $table->foreignUuid('mitra_id')->constrained('mitras')->onDelete('cascade');
            $table->foreignUuid('lokasi_id')->constrained('lokasis')->onDelete('cascade');

            $table->string('judul_lowongan');
            $table->text('deskripsi_lowongan');
            $table->string('tipe_pekerjaan'); // Contoh: Full-time, Internship, dll.

            // Gaji dipecah jadi dua kolom numerik biar bisa difilter
            $table->bigInteger('gaji_min')->default(0);
            $table->bigInteger('gaji_max')->default(0);

            // Kolom pendukung lainnya
            $table->date('tanggal_expired');
            $table->enum('status_lowongan', ['pending', 'verified', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongans');
    }
};