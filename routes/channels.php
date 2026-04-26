<?php

use Illuminate\Support\Facades\Broadcast;

/**
 * Channel Privat User (Bawaan Laravel untuk Notifikasi)
 */
Broadcast::channel('App.Models.User.{userId}', function ($user, $userId) {
    return (string) $user->id === (string) $userId;
});

/**
 * Channel Chat Utama
 * Pastikan di Vue kamu memanggil: Echo.private(`chat.${authUser.id}`)
 */
Broadcast::channel('chat.{userId}', function ($user, $userId) {
    // Logika: User hanya boleh join ke channel yang ID-nya sama dengan ID dia sendiri
    return (string) $user->id === (string) $userId;
});

/**
 * Channel Percakapan (Untuk fitur 'Typing...' atau 'Presence')
 */
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = \App\Models\Conversation::find($conversationId);

    if (!$conversation) {
        return false;
    }

    Broadcast::channel('online', function ($user) {
        // Kalau berhasil gabung, kembalikan data user (ID wajib ada)
        return [
            'id' => (string) $user->id,
            'name' => $user->name
        ];
    });

    // User harus salah satu dari pengirim atau penerima di percakapan ini
    return (string) $user->id === (string) $conversation->sender_id ||
        (string) $user->id === (string) $conversation->receiver_id;
});