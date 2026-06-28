<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameMoveEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $roomCode,
        public int $row,
        public int $col,
        public string $player,
        public string $currentTurn,
        public ?string $winner = null,
        public string $status = 'playing',
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('game-room.' . $this->roomCode);
    }

    public function broadcastAs(): string
    {
        return 'move';
    }
}
