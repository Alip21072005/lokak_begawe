<?php

namespace App\Http\Controllers;

use App\Events\MessageRead;
use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
    /**
     * Menampilkan daftar percakapan (Inbox)
     */
    public function index(): Response
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
            },
        ])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->orderByDesc('last_message_at')
            ->get()
            ->map(function ($conv) use ($user) {
                $lastMessage = $conv->messages->first();

                $unreadCount = Message::where('conversation_id', $conv->id)
                    ->where('sender_id', '!=', $user->id)
                    ->where('read', false)
                    ->count();

                return [
                    'id' => $conv->id,
                    'sender_id' => $conv->sender_id,
                    'receiver_id' => $conv->receiver_id,
                    'updated_at' => $conv->updated_at,
                    'last_message_at' => $conv->last_message_at,
                    'sender' => $conv->sender,
                    'receiver' => $conv->receiver,
                    'last_message' => $lastMessage ? [
                        'id' => $lastMessage->id,
                        'body' => $lastMessage->body,
                        'attachment_url' => $lastMessage->attachment_url,
                        'attachment_type' => $lastMessage->attachment_type,
                        'created_at' => $lastMessage->created_at,
                        'sender_id' => $lastMessage->sender_id,
                    ] : null,
                    'unread_count' => $unreadCount,
                ];
            })
            ->values();

        return Inertia::render('Chat/Index', [
            'conversations' => $conversations,
            'auth' => [
                'user' => $user->load(['pelamar', 'mitra']),
            ],
        ]);
    }

    /**
     * API untuk mengambil isi pesan dalam satu percakapan
     */
    public function show(string $id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            $conversation = Conversation::with([
                'messages' => function ($query) {
                    $query->orderBy('created_at', 'asc');
                },
                'messages.sender.pelamar',
                'messages.sender.mitra',
                'sender.pelamar',
                'sender.mitra',
                'receiver.pelamar',
                'receiver.mitra',
            ])->findOrFail($id);

            // Keamanan: pastikan user adalah partisipan
            if ($user->id !== $conversation->sender_id && $user->id !== $conversation->receiver_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Mark as read pesan lawan bicara
            Message::where('conversation_id', $conversation->id)
                ->where('sender_id', '!=', $user->id)
                ->where('read', false)
                ->update(['read' => true]);

            $messages = $conversation->messages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'conversation_id' => $msg->conversation_id,
                    'sender_id' => $msg->sender_id,
                    'body' => $msg->body,
                    'attachment_url' => $msg->attachment_url,
                    'attachment_type' => $msg->attachment_type,
                    'type' => $msg->type,
                    'read' => (bool) $msg->read,
                    'created_at' => $msg->created_at,
                    'updated_at' => $msg->updated_at,
                ];
            })->values();

            return response()->json([
                'conversation' => [
                    'id' => $conversation->id,
                    'sender_id' => $conversation->sender_id,
                    'receiver_id' => $conversation->receiver_id,
                    'sender' => $conversation->sender,
                    'receiver' => $conversation->receiver,
                ],
                'messages' => $messages,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Percakapan tidak ditemukan'], 404);
        }
    }

    /**
     * Simpan & broadcast pesan (JSON endpoint)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'body' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:5120'], // 5MB
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $myId = $user->id;
        $targetId = $validated['receiver_id'];

        $body = trim((string) ($validated['body'] ?? ''));
        $hasFile = $request->hasFile('file');

        // Cegah pesan kosong total
        if ($body === '' && !$hasFile) {
            return response()->json(['error' => 'Pesan tidak boleh kosong.'], 422);
        }

        // Cari / buat conversation
        $conversation = Conversation::where(function ($q) use ($myId, $targetId) {
            $q->where('sender_id', $myId)->where('receiver_id', $targetId);
        })->orWhere(function ($q) use ($myId, $targetId) {
            $q->where('sender_id', $targetId)->where('receiver_id', $myId);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $myId,
                'receiver_id' => $targetId,
                'last_message_at' => now(),
            ]);
        } else {
            $conversation->update(['last_message_at' => now()]);
        }

        // Handle attachment
        $attachmentUrl = null;
        $attachmentType = null;
        $messageType = 'text';

        if ($hasFile) {
            $file = $request->file('file');
            $path = $file->store('attachments', 'public');
            $attachmentUrl = $path;

            $mime = (string) $file->getMimeType();
            $attachmentType = str_contains($mime, 'image') ? 'image' : 'file';
            $messageType = $attachmentType;
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $myId,
            'body' => $body,
            'attachment_url' => $attachmentUrl,
            'attachment_type' => $attachmentType,
            'type' => $messageType,
            'read' => false,
        ]);

        $message->load(['sender.pelamar', 'sender.mitra']);

        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Throwable $e) {
            Log::warning('Chat broadcast failed: ' . $e->getMessage());
        }

        return response()->json([
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_id' => $message->sender_id,
            'body' => $message->body,
            'attachment_url' => $message->attachment_url,
            'attachment_type' => $message->attachment_type,
            'type' => $message->type,
            'read' => (bool) $message->read,
            'created_at' => $message->created_at,
            'updated_at' => $message->updated_at,
        ]);
    }

    /**
     * Menandai pesan sebagai dibaca
     */
    public function markAsRead(string $conversationId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $myId = $user->id;

        try {
            $conversation = Conversation::findOrFail($conversationId);

            if ($conversation->sender_id !== $myId && $conversation->receiver_id !== $myId) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
            }

            $targetId = $conversation->sender_id === $myId
                ? $conversation->receiver_id
                : $conversation->sender_id;

            $affectedRows = Message::where('conversation_id', $conversationId)
                ->where('sender_id', '!=', $myId)
                ->where('read', false)
                ->update(['read' => true]);

            if ($affectedRows > 0) {
                broadcast(new MessageRead($conversationId, $myId, $targetId))->toOthers();
            }

            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menandai pesan sebagai dibaca.',
            ], 500);
        }
    }
}