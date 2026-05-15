<?php

namespace App\Jobs\Sport;

use App\Models\Sport\SportMatch;
use App\Models\Sport\UserSportProfile;
use App\Services\FcmNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GoalNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public string $queue = 'high';

    public function __construct(
        private string $matchId,
        private string $scoringTeamId,
        private int $homeScore,
        private int $awayScore,
    ) {}

    public function handle(FcmNotificationService $fcm): void
    {
        $match = SportMatch::with(['homeTeam', 'awayTeam'])->find($this->matchId);
        if (!$match) return;

        $title = '⚽ GOAL!';
        $body = "{$match->homeTeam->name} {$this->homeScore} - {$this->awayScore} {$match->awayTeam->name}";

        $users = UserSportProfile::where(function ($q) use ($match) {
            $q->whereJsonContains('favorite_teams', $match->home_team_id)
              ->orWhereJsonContains('favorite_teams', $match->away_team_id);
        })->with('fcmTokens')->get();

        $sent = 0;
        foreach ($users as $user) {
            if ($this->exceedsDailyLimit($user->id)) continue;
            foreach ($user->fcmTokens as $token) {
                $fcm->sendToToken($token->token, compact('title', 'body'), [
                    'type' => 'goal', 'match_id' => $this->matchId,
                ]);
                $sent++;
            }
        }

        Log::info("GoalNotification sent to {$sent} tokens for match {$this->matchId}");
    }

    private function exceedsDailyLimit(int $userId): bool
    {
        $key = "sport_push_count:{$userId}:" . now()->toDateString();
        $count = (int) cache($key, 0);
        if ($count >= 20) return true;
        cache([$key => $count + 1], now()->endOfDay());
        return false;
    }
}
