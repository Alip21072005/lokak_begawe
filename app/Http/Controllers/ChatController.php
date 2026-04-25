<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use App\Events\MessageRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ChatController extends Controller
{
    /**
     * Menampilkan daftar percakapan (Inbox)
     * Diakses oleh Pelamar, Mitra, maupun Admin
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $conversations = Conversation::with([
            'sender.pelamar',
            'sender.mitra',
            'receiver.pelamar',
            'receiver.mitra',
            'messages' => function ($query) {
                $query->latest()->limit(1);
            }
        ])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->orderBy('last_message_at', 'desc')
            ->get();

        return Inertia::render('Chat/Index', [
            'conversations' => $conversations,
            'auth' => [
                'user' => $user->load(['pelamar', 'mitra'])
            ]
        ]);
    }

    /**
     * API untuk mengambil isi pesan dalam satu percakapan
     */
    public function show($id)
    {
        try {
            $conversation = Conversation::with([
                'messages.sender.pelamar',
                'messages.sender.mitra',
                'sender.pelamar',
                'sender.mitra',
                'receiver.pelamar',
                'receiver.mitra'
            ])->findOrFail($id);

            // Keamanan: Pastikan user adalah partisipan dalam chat ini
            if (Auth::id() !== $conversation->sender_id && Auth::id() !== $conversation->receiver_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Tandai pesan sebagai sudah dibaca (kecuali pesan milik sendiri)
            Message::where('conversation_id', $id)
                ->where('sender_id', '!=', Auth::id())
                ->where('read', false)
                ->update(['read' => true]);

            return response()->json([
                'messages' => $conversation->messages,
                'conversation' => $conversation
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Percakapan tidak ditemukan'], 404);
        }
    }

    /**
     * Simpan & Broadcast Pesan
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body'        => 'required|string',
            'file'        => 'nullable|file|max:5120', // Max 5MB
        ]);

        $myId = Auth::id();
        $targetId = $request->receiver_id;

        // 1. Cari atau buat percakapan
        $conversation = Conversation::where(function ($q) use ($myId, $targetId) {
            $q->where('sender_id', $myId)->where('receiver_id', $targetId);
        })->orWhere(function ($q) use ($myId, $targetId) {
            $q->where('sender_id', $targetId)->where('receiver_id', $myId);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id'       => $myId,
                'receiver_id'     => $targetId,
                'last_message_at' => now(),
            ]);
        } else {
            $conversation->update(['last_message_at' => now()]);
        }

        // 2. Handle Attachment (Jika ada)
        $attachmentUrl = null;
        $attachmentType = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('attachments', 'public');
            $attachmentUrl = $path;
            $attachmentType = str_contains($file->getMimeType(), 'image') ? 'image' : 'file';
        }

        // 3. Simpan Pesan
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => $myId,
            'body'            => $request->body,
            'attachment_url'  => $attachmentUrl,
            'attachment_type' => $attachmentType,
            'type'            => 'text',
        ]);

        // Eager load profil pengirim untuk keperluan frontend
        $message->load(['sender.pelamar', 'sender.mitra']);

        // 4. Real-time Broadcast dengan Error Handling
        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Exception $e) {
            Log::error("Broadcasting failed: " . $e->getMessage());
        }

        return response()->json($message);
    }

    /**
     * Menandai pesan sebagai telah dibaca (Real-time Centang Biru)
     */
    public function markAsRead($conversationId)
    {
        $myId = Auth::id();

        try {
            $conversation = Conversation::findOrFail($conversationId);
            $targetId = ($conversation->sender_id === $myId) ? $conversation->receiver_id : $conversation->sender_id;

            $affectedRows = Message::where('conversation_id', $conversationId)
                ->where('sender_id', '!=', $myId)
                ->where('read', false)
                ->update(['read' => true]);

            if ($affectedRows > 0) {
                broadcast(new MessageRead($conversationId, $myId, $targetId))->toOthers();
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}