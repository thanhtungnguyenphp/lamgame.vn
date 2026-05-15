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

class MatchEndNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(private string $matchId) {}

    public function handle(FcmNotificationService $fcm): void
    {
        $match = SportMatch::with(['homeTeam', 'awayTeam'])->find($this->matchId);
        if (!$match || $match->status !== 'finished') return;

        $title = '🏁 Kết thúc';
        $body = "{$match->homeTeam->name} {$match->home_score} - {$match->away_score} {$match->awayTeam->name}";

        $users = UserSportProfile::where(function ($q) use ($match) {
            $q->whereJsonContains('favorite_teams', $match->home_team_id)
              ->orWhereJsonContains('favorite_teams', $match->away_team_id);
        })->with('fcmTokens')->get();

        foreach ($users as $user) {
            foreach ($user->fcmTokens as $token) {
                $fcm->sendToToken($token->token, compact('title', 'body'), [
                    'type' => 'match_end', 'match_id' => $this->matchId,
                ]);
            }
        }
    }
}
