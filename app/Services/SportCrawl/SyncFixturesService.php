<?php

namespace App\Services\SportCrawl;

use App\Models\Sport\SportMatch;
use Illuminate\Support\Str;

class SyncFixturesService extends SportDataService
{
    protected string $crawler = 'sync-fixtures';

    public function run(): void
    {
        $start = microtime(true);
        $this->resetCounters();

        $leagues = config('sport-crawl.leagues');

        foreach ($leagues as $leagueId) {
            $data = $this->apiFootball('/fixtures', [
                'league' => $leagueId,
                'season' => config('sport-crawl.season'),
                'from' => now()->toDateString(),
                'to' => now()->addDays(7)->toDateString(),
            ]);

            if (!$data) {
                continue;
            }

            $this->fetched += count($data);

            foreach ($data as $fixture) {
                $this->processFixture($fixture);
            }

            // Respect rate limit (free plan: 10 req/min)
            usleep(7_000_000);
        }

        $duration = (int) ((microtime(true) - $start) * 1000);
        $this->log($this->fetched > 0 ? 'success' : 'partial', null, $duration);
    }

    protected function processFixture(array $fixture): void
    {
        $fixtureData = $fixture['fixture'] ?? [];
        $teams = $fixture['teams'] ?? [];
        $goals = $fixture['goals'] ?? [];

        $externalId = (string) ($fixtureData['id'] ?? '');
        if (!$externalId) {
            $this->skipped++;
            return;
        }

        $homeTeamId = $this->mapTeamId($teams['home']['id'] ?? '', 'api_football');
        $awayTeamId = $this->mapTeamId($teams['away']['id'] ?? '', 'api_football');

        if (!$homeTeamId || !$awayTeamId) {
            $this->skipped++;
            return;
        }

        $status = $this->mapStatus($fixtureData['status']['short'] ?? 'NS');

        $match = SportMatch::updateOrCreate(
            ['external_id' => $externalId],
            [
                'id' => SportMatch::where('external_id', $externalId)->value('id') ?? Str::slug($homeTeamId . '-vs-' . $awayTeamId . '-' . $externalId),
                'home_team_id' => $homeTeamId,
                'away_team_id' => $awayTeamId,
                'league_id' => $this->mapLeagueId($fixture['league']['id'] ?? null),
                'sport_id' => 'football',
                'status' => $status,
                'home_score' => $goals['home'] ?? 0,
                'away_score' => $goals['away'] ?? 0,
                'start_time' => $fixtureData['date'] ?? now(),
                'venue' => $fixtureData['venue']['name'] ?? null,
                'referee' => $fixtureData['referee'] ?? null,
                'source' => 'api-football',
                'synced_at' => now(),
            ]
        );

        // Broadcast score change for live matches
        if ($status === 'live' && !$match->wasRecentlyCreated) {
            $oldHome = (int) $match->getOriginal('home_score');
            $oldAway = (int) $match->getOriginal('away_score');
            if ($oldHome !== ($goals['home'] ?? 0) || $oldAway !== ($goals['away'] ?? 0)) {
                event(new \App\Events\Sport\ScoreUpdated($match->id, $goals['home'] ?? 0, $goals['away'] ?? 0));
            }
        }

        $match->wasRecentlyCreated ? $this->created++ : $this->updated++;
    }

    protected function mapStatus(string $short): string
    {
        return match ($short) {
            'NS', 'TBD' => 'scheduled',
            '1H', '2H', 'ET', 'P', 'LIVE' => 'live',
            'HT' => 'halftime',
            'FT', 'AET', 'PEN' => 'finished',
            'PST' => 'postponed',
            'CANC' => 'cancelled',
            default => 'scheduled',
        };
    }

    protected function mapLeagueId(?int $externalLeagueId): ?string
    {
        if (!$externalLeagueId) return null;
        $league = \App\Models\Sport\League::whereJsonContains('external_ids->api_football', $externalLeagueId)->first();
        return $league?->id;
    }
}
