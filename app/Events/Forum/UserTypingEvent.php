<?php

namespace App\Events\Forum;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTypingEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $postId,
        public string $userName,
    ) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel("forum.post.{$this->postId}")];
    }

    public function broadcastAs(): string
    {
        return 'user.typing';
    }
}
