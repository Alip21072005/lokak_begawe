<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory, HasUuids;

    // TAMBAHKAN attachment_url DAN attachment_type DI SINI!
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'body',
        'read',
        'type',
        'attachment_url',
        'attachment_type'
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'sender_id' => 'string',
            'conversation_id' => 'string',
            'read' => 'boolean',
        ];
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}