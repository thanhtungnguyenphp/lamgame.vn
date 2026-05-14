<?php

namespace App\Services\SportCrawl;

use App\Models\Sport\Standing;

class SyncStandingsService extends SportDataService
{
    protected string $crawler = 'sync-standings';

    public function run(): void
    {
        $start = microtime(true);
        $this->resetCounters();

        foreach (config('sport-crawl.leagues') as $leagueId) {
            $data = $this->apiFootball('/standings', [
                'league' => $leagueId,
                'season' => config('sport-crawl.season'),
            ]);

            if (!$data || empty($data[0]['league']['standings'][0])) {
                continue;
            }

            $standings = $data[0]['league']['standings'][0];
            $this->fetched += count($standings);
            $localLeagueId = $this->mapLeagueId($leagueId);

            foreach ($standings as $entry) {
                $teamId = $this->mapTeamId($entry['team']['id'] ?? '', 'api_football');
                if (!$teamId || !$localLeagueId) {
                    $this->skipped++;
                    continue;
                }

                $standing = Standing::updateOrCreate(
                    ['league_id' => $localLeagueId, 'team_id' => $teamId],
                    [
                        'sport_id' => 'football',
                        'season' => config('sport-crawl.season'),
                        'position' => $entry['rank'] ?? 0,
                        'played' => $entry['all']['played'] ?? 0,
                        'won' => $entry['all']['win'] ?? 0,
                        'drawn' => $entry['all']['draw'] ?? 0,
                        'lost' => $entry['all']['lose'] ?? 0,
                        'goals_for' => $entry['all']['goals']['for'] ?? 0,
                        'goals_against' => $entry['all']['goals']['against'] ?? 0,
                        'goal_difference' => ($entry['all']['goals']['for'] ?? 0) - ($entry['all']['goals']['against'] ?? 0),
                        'points' => $entry['points'] ?? 0,
                        'form' => $entry['form'] ?? null,
                    ]
                );

                $standing->wasRecentlyCreated ? $this->created++ : $this->updated++;
            }
        }

        $duration = (int) ((microtime(true) - $start) * 1000);
        $this->log($this->fetched > 0 ? 'success' : 'partial', null, $duration);
    }

    protected function mapLeagueId(int $externalLeagueId): ?string
    {
        $league = \App\Models\Sport\League::whereJsonContains('external_ids->api_football', $externalLeagueId)->first();
        return $league?->id;
    }
}
