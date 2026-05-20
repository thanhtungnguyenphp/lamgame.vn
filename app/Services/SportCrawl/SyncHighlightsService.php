<?php

namespace App\Services\SportCrawl;

use App\Models\Sport\Highlight;

class SyncHighlightsService extends SportDataService
{
    protected string $crawler = 'sync-highlights';

    public function run(): void
    {
        $start = microtime(true);
        $this->resetCounters();

        $body = $this->httpGet(config('sport-crawl.scorebat.base_url'));
        if (!$body) {
            $this->log('failed', 'Scorebat API unreachable', (int) ((microtime(true) - $start) * 1000));
            return;
        }

        $feed = json_decode($body, true);
        $items = is_array($feed) ? $feed : [];
        $this->fetched = count($items);

        foreach ($items as $item) {
            $embed = $item['videos'][0]['embed'] ?? $item['embed'] ?? null;
            if (!$embed) {
                $this->skipped++;
                continue;
            }

            // Extract iframe src from embed HTML
            $videoUrl = $embed;
            if (preg_match('/src=[\'"]([^\'"]+)[\'"]/', $embed, $m)) {
                $videoUrl = $m[1];
            }

            // Dedup by source_url
            if (Highlight::where('source_url', $videoUrl)->exists()) {
                $this->skipped++;
                continue;
            }

            Highlight::create([
                'title' => $item['title'] ?? 'Highlight',
                'thumbnail_url' => $item['thumbnail'] ?? null,
                'video_url' => $videoUrl,
                'source_url' => $videoUrl,
                'sport_id' => 'football',
                'match_id' => $this->fuzzyMatchId($item['title'] ?? ''),
            ]);

            $this->created++;
        }

        $duration = (int) ((microtime(true) - $start) * 1000);
        $this->log('success', null, $duration);
    }

    protected function fuzzyMatchId(string $title): ?string
    {
        // Try to find match by team names in title
        $teams = \App\Models\Sport\Team::pluck('name', 'id');
        $matchedTeams = [];

        foreach ($teams as $id => $name) {
            if (stripos($title, $name) !== false) {
                $matchedTeams[] = $id;
            }
        }

        if (count($matchedTeams) >= 2) {
            return \App\Models\Sport\SportMatch::where('home_team_id', $matchedTeams[0])
                ->where('away_team_id', $matchedTeams[1])
                ->where('start_time', '>=', now()->subDays(3))
                ->value('id');
        }

        return null;
    }
}
