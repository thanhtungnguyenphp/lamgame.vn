<?php

namespace App\Jobs\Sport;

use App\Models\Sport\SportMatch;
use App\Models\Sport\UserSportReminder;
use App\Services\FcmNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MatchStartNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(private string $matchId) {}

    public function handle(FcmNotificationService $fcm): void
    {
        $match = SportMatch::with(['homeTeam', 'awayTeam'])->find($this->matchId);
        if (!$match || $match->status !== 'scheduled') return;

        $title = '🏟️ Sắp bắt đầu (15 phút)';
        $body = "{$match->homeTeam->name} vs {$match->awayTeam->name}";

        // Users with reminders for this match
        $reminders = UserSportReminder::where('match_id', $this->matchId)
            ->with('profile.fcmTokens')
            ->get();

        foreach ($reminders as $reminder) {
            foreach ($reminder->profile->fcmTokens ?? [] as $token) {
                $fcm->sendToToken($token->token, compact('title', 'body'), [
                    'type' => 'match_start', 'match_id' => $this->matchId,
                ]);
            }
        }

        // Users with favorite teams
        $teamIds = [$match->home_team_id, $match->away_team_id];
        $profiles = \App\Models\Sport\UserSportProfile::where(function ($q) use ($teamIds) {
            foreach ($teamIds as $id) {
                $q->orWhereJsonContains('favorite_teams', $id);
            }
        })->with('fcmTokens')->get();

        foreach ($profiles as $user) {
            foreach ($user->fcmTokens as $token) {
                $fcm->sendToToken($token->token, compact('title', 'body'), [
                    'type' => 'match_start', 'match_id' => $this->matchId,
                ]);
            }
        }
    }
}
