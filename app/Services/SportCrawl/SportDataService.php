<?php

namespace App\Services\SportCrawl;

use App\Models\Sport\SportCrawlLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SportDataService
{
    protected string $crawler = 'base';
    protected int $fetched = 0;
    protected int $created = 0;
    protected int $updated = 0;
    protected int $skipped = 0;

    protected function apiFootball(string $endpoint, array $params = []): ?array
    {
        $config = config('sport-crawl.api_football');
        try {
            $response = Http::timeout(config('sport-crawl.timeout'))
                ->retry(config('sport-crawl.retry.times'), config('sport-crawl.retry.sleep_ms'), throw: false)
                ->withHeaders(['x-apisports-key' => $config['key']])
                ->get($config['base_url'] . $endpoint, $params);

            if ($response->failed()) {
                Log::error("SportCrawl [{$this->crawler}] API-Football failed", [
                    'endpoint' => $endpoint,
                    'status' => $response->status(),
                ]);
                return null;
            }

            return $response->json('response');
        } catch (\Exception $e) {
            Log::error("SportCrawl [{$this->crawler}] API-Football exception", ['error' => $e->getMessage()]);
            return null;
        }
    }

    protected function httpGet(string $url, array $headers = []): ?string
    {
        try {
            $response = Http::timeout(config('sport-crawl.timeout'))
                ->retry(config('sport-crawl.retry.times'), config('sport-crawl.retry.sleep_ms'), throw: false)
                ->withHeaders($headers)
                ->get($url);

            return $response->successful() ? $response->body() : null;
        } catch (\Exception $e) {
            Log::error("SportCrawl [{$this->crawler}] HTTP failed", ['url' => $url, 'error' => $e->getMessage()]);
            return null;
        }
    }

    protected function resetCounters(): void
    {
        $this->fetched = $this->created = $this->updated = $this->skipped = 0;
    }

    protected function log(string $status, ?string $error = null, int $durationMs = 0): SportCrawlLog
    {
        return SportCrawlLog::create([
            'crawler' => $this->crawler,
            'source' => 'api-football',
            'status' => $status,
            'items_fetched' => $this->fetched,
            'items_created' => $this->created,
            'items_updated' => $this->updated,
            'items_skipped' => $this->skipped,
            'error_message' => $error,
            'duration_ms' => $durationMs,
        ]);
    }

    protected function mapTeamId(string $externalId, string $source = 'api_football'): ?string
    {
        $team = \App\Models\Sport\Team::whereJsonContains('external_ids->' . $source, (int) $externalId)->first();
        return $team?->id;
    }
}
