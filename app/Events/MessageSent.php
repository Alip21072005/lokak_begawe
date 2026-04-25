<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        // Gunakan toArray() agar ID UUID tidak berubah jadi objek saat broadcast
        $this->message = $message->load(['sender'])->toArray();
    }

    public function broadcastOn(): array
    {
        // Ambil ID dari array message
        return [
            new PrivateChannel('chat.' . $this->message['sender_id']),
            new PrivateChannel('chat.' . $this->message['conversation']['receiver_id'] ?? $this->message['conversation_id']),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}