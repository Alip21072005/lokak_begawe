<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversation_id;
    public $reader_id;
    public $target_id;

    public function __construct($conversation_id, $reader_id, $target_id)
    {
        $this->conversation_id = $conversation_id;
        $this->reader_id = $reader_id;
        $this->target_id = $target_id; // ID orang yang pesan-nya dibaca
    }

    public function broadcastOn(): array
    {
        // Tembakkan notifikasi centang biru ke private channel pengirim asli
        return [
            new PrivateChannel('chat.' . $this->target_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageRead';
    }
}