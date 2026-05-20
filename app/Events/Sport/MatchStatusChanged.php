<?php

namespace App\Events\Sport;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MatchStatusChanged implements ShouldBroadcast
{
    use SerializesModels;

    public function __construct(
        public string $matchId,
        public string $status, // live, halftime, finished
        public ?int $minute = null,
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel("sport.match.{$this->matchId}");
    }

    public function broadcastAs(): string
    {
        return 'match.status';
    }
}
