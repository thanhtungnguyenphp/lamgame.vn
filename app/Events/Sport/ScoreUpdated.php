<?php

namespace App\Events\Sport;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class ScoreUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public function __construct(
        public string $matchId,
        public int $homeScore,
        public int $awayScore,
        public ?int $minute = null,
        public ?string $event = null, // goal, red_card, penalty, etc.
        public ?string $scorer = null,
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel("sport.match.{$this->matchId}");
    }

    public function broadcastAs(): string
    {
        return 'score.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'match_id' => $this->matchId,
            'home_score' => $this->homeScore,
            'away_score' => $this->awayScore,
            'minute' => $this->minute,
            'event' => $this->event,
            'scorer' => $this->scorer,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
