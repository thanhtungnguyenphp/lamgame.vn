<?php

namespace App\Events\Forum;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentVotedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $postId,
        public int $commentId,
        public int $voteCount,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel("forum.post.{$this->postId}")];
    }

    public function broadcastAs(): string
    {
        return 'comment.voted';
    }
}
