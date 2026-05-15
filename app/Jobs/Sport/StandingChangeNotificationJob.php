<?php

namespace App\Jobs\Sport;

use App\Models\Sport\UserSportProfile;
use App\Services\FcmNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StandingChangeNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(
        private string $teamId,
        private string $leagueId,
        private int $oldPosition,
        private int $newPosition,
    ) {}

    public function handle(FcmNotificationService $fcm): void
    {
        $direction = $this->newPosition < $this->oldPosition ? '📈' : '📉';
        $team = \App\Models\Sport\Team::find($this->teamId);
        if (!$team) return;

        $title = "{$direction} Thay đổi BXH";
        $body = "{$team->name}: #{$this->oldPosition} → #{$this->newPosition}";

        $users = UserSportProfile::whereJsonContains('favorite_teams', $this->teamId)
            ->with('fcmTokens')
            ->get();

        foreach ($users as $user) {
            foreach ($user->fcmTokens as $token) {
                $fcm->sendToToken($token->token, compact('title', 'body'), [
                    'type' => 'standing_change', 'team_id' => $this->teamId, 'league_id' => $this->leagueId,
                ]);
            }
        }
    }
}
