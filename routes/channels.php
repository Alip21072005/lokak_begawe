<?php

use Illuminate\Support\Facades\Broadcast;

/**
 * Channel Privat User (Bawaan Laravel)
 */
Broadcast::channel('App.Models.User.{userId}', function ($user, $userId) {
    return (string) $user->id === (string) $userId;
});

/**
 * Channel Chat Utama (Untuk menerima pesan real-time)
 */
Broadcast::channel('chat.{userId}', function ($user, $userId) {
    return (string) $user->id === (string) $userId;
});

/**
 * Channel Presence (Status Online/Offline)
 * PERBAIKAN: Dikeluarkan dari dalam channel conversation
 */
Broadcast::channel('online', function ($user) {
    return [
        'id' => (string) $user->id,
        'name' => $user->name
    ];
});

/**
 * Channel Percakapan (Ruang bersama untuk fitur 'Typing...')
 */
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = \App\Models\Conversation::find($conversationId);

    if (!$conversation) {
        return false;
    }

    // Izinkan masuk jika user adalah pengirim atau penerima di percakapan ini
    return (string) $user->id === (string) $conversation->sender_id ||
           (string) $user->id === (string) $conversation->receiver_id;
});