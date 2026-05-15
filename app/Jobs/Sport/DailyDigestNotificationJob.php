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

class DailyDigestNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function handle(FcmNotificationService $fcm): void
    {
        $todayMatches = SportMatch::whereDate('start_time', today())
            ->where('status', 'scheduled')
            ->with(['homeTeam', 'awayTeam'])
            ->get();

        if ($todayMatches->isEmpty()) return;

        $users = UserSportProfile::whereNotNull('favorite_teams')
            ->with('fcmTokens')
            ->get();

        foreach ($users as $user) {
            $favoriteTeams = $user->favorite_teams ?? [];
            $relevantMatches = $todayMatches->filter(fn ($m) =>
                in_array($m->home_team_id, $favoriteTeams) || in_array($m->away_team_id, $favoriteTeams)
            );

            if ($relevantMatches->isEmpty()) continue;

            $title = "⚽ Hôm nay: {$relevantMatches->count()} trận";
            $lines = $relevantMatches->take(3)->map(fn ($m) =>
                "{$m->homeTeam->name} vs {$m->awayTeam->name}"
            );
            $body = $lines->implode(', ');
            if ($relevantMatches->count() > 3) $body .= '...';

            foreach ($user->fcmTokens as $token) {
                $fcm->sendToToken($token->token, compact('title', 'body'), [
                    'type' => 'daily_digest', 'match_count' => $relevantMatches->count(),
                ]);
            }
        }
    }
}
