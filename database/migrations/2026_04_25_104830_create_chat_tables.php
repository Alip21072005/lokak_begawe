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
        // Tabel untuk mengelompokkan chat antara 2 user
        Schema::create('conversations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('receiver_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });

        // Tabel isi pesan
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignUuid('sender_id')->constrained('users')->onDelete('cascade');
            $table->text('body');
            $table->boolean('read')->default(false);
            $table->string('type')->default('text'); // Bisa dikembangkan untuk 'image'/'file'
            $table->string('attachment_url')->nullable();
            $table->string('attachment_type')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('chat_tables');
    }
};