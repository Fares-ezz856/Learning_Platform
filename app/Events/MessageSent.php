<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        // نمرر الرسالة مع بيانات الراسل (Instructor أو Student)
        $this->message = $message->load('sender');
    }

    public function broadcastOn(): array
    {
        // قناة عامة للكل (Instructor & Student)
        return [
            new Channel('chat-room'),
        ];
    }
}
