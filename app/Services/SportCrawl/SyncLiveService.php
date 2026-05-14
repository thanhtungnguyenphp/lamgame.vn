<?php

namespace App\Services\SportCrawl;

use App\Models\Sport\SportMatch;
use Illuminate\Support\Facades\Cache;

class SyncLiveService extends SportDataService
{
    protected string $crawler = 'sync-live';

    public function run(): void
    {
        if (!Cache::get('sport:has_live_matches', false)) {
            return;
        }

        $start = microtime(true);
        $this->resetCounters();

        $data = $this->apiFootball('/fixtures', ['live' => 'all']);

        if (!$data) {
            $duration = (int) ((microtime(true) - $start) * 1000);
            $this->log('failed', 'API returned null', $duration);
            return;
        }

        $this->fetched = count($data);
        $liveExternalIds = [];

        foreach ($data as $fixture) {
            $externalId = (string) ($fixture['fixture']['id'] ?? '');
            if (!$externalId) {
                $this->skipped++;
                continue;
            }

            $liveExternalIds[] = $externalId;
            $match = SportMatch::where('external_id', $externalId)->first();
            if (!$match) {
                $this->skipped++;
                continue;
            }

            $goals = $fixture['goals'] ?? [];
            $fixtureData = $fixture['fixture'] ?? [];
            $oldHome = $match->home_score;
            $oldAway = $match->away_score;

            $match->update([
                'status' => $this->mapStatus($fixtureData['status']['short'] ?? 'LIVE'),
                'minute' => $fixtureData['status']['elapsed'] ?? null,
                'home_score' => $goals['home'] ?? $match->home_score,
                'away_score' => $goals['away'] ?? $match->away_score,
                'synced_at' => now(),
            ]);

            // Dispatch goal notification if score changed
            if (($goals['home'] ?? $oldHome) != $oldHome || ($goals['away'] ?? $oldAway) != $oldAway) {
                // TODO: dispatch SendGoalNotification job
            }

            $this->updated++;
        }

        // Mark finished matches
        $finishedCount = SportMatch::where('status', 'live')
            ->whereNotNull('external_id')
            ->whereNotIn('external_id', $liveExternalIds)
            ->update(['status' => 'finished', 'synced_at' => now()]);

        // Clear live flag if no more live matches
        if (empty($liveExternalIds)) {
            Cache::forget('sport:has_live_matches');
        }

        $duration = (int) ((microtime(true) - $start) * 1000);
        $this->log('success', null, $duration);
    }

    protected function mapStatus(string $short): string
    {
        return match ($short) {
            '1H', '2H', 'ET', 'P', 'LIVE' => 'live',
            'HT' => 'halftime',
            'FT', 'AET', 'PEN' => 'finished',
            default => 'live',
        };
    }
}
