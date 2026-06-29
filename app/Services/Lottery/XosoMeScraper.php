<?php

namespace App\Services\Lottery;

use App\Models\LotteryDraw;
use App\Models\LotteryResult;
use App\Models\LotteryScrapeLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XosoMeScraper
{
    private array $urls = [
        'mega645'   => '/kqxs-mega-645-ket-qua-xo-so-mega-6-45-vietlott-ngay-hom-nay.html',
        'power655'  => '/kqxs-power-6-55-ket-qua-xo-so-power-6-55-vietlott-ngay-hom-nay.html',
        'max3d'     => '/kqxs-max3d-ket-qua-xo-so-max-3d-vietlott.html',
        'max3d_pro' => '/xo-so-max3d-pro.html',
    ];

    // GitHub raw JSONL for keno (updated daily)
    private array $githubPaths = [
        'keno'      => '/keno.jsonl',
        'mega645'   => '/power645.jsonl',
        'power655'  => '/power655.jsonl',
        'max3d'     => '/3d.jsonl',
        'max3d_pro' => '/3d_pro.jsonl',
    ];

    /**
     * Scrape kết quả từ xoso.me (Mega/Power/Max3D) hoặc GitHub (Keno + fallback).
     */
    public function scrape(string $game, ?string $date = null): bool
    {
        $date = $date ?: Carbon::today()->toDateString();

        // Keno: always use GitHub raw data (xoso.me requires form submit)
        if ($game === 'keno') {
            return $this->scrapeFromGithub($game, $date);
        }

        // Try xoso.me first, fallback to GitHub
        $result = $this->scrapeFromXosoMe($game, $date);
        if (!$result) {
            $result = $this->scrapeFromGithub($game, $date);
        }
        return $result;
    }

    private function scrapeFromXosoMe(string $game, string $date): bool
    {
        $path = $this->urls[$game] ?? null;
        if (!$path) return false;

        $baseUrl = config('lottery.scrape.sources.xoso_me');
        $url = $baseUrl . $path;
        $start = microtime(true);

        try {
            $response = Http::timeout(config('lottery.scrape.timeout'))
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'Accept'     => 'text/html',
                ])
                ->get($url);

            if ($response->failed()) {
                $this->logScrape('xoso.me', $url, 'failed', $this->elapsed($start), "HTTP {$response->status()}");
                return false;
            }

            $html = $response->body();
            $elapsed = $this->elapsed($start);
            $parsed = $this->parseXosoMe($game, $html, $date);

            if (!$parsed) {
                $this->logScrape('xoso.me', $url, 'failed', $elapsed, 'No results parsed');
                return false;
            }

            $this->saveDraw($game, $parsed);
            $this->logScrape('xoso.me', $url, 'success', $elapsed);
            return true;

        } catch (\Exception $e) {
            $this->logScrape('xoso.me', $url, 'failed', $this->elapsed($start), $e->getMessage());
            Log::error("XosoMeScraper failed: {$game}", ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function scrapeFromGithub(string $game, string $date): bool
    {
        $path = $this->githubPaths[$game] ?? null;
        if (!$path) return false;

        $baseUrl = config('lottery.scrape.sources.github_raw');
        $url = $baseUrl . $path;
        $start = microtime(true);

        try {
            $response = Http::timeout(15)->get($url);

            if ($response->failed()) {
                $this->logScrape('github', $url, 'failed', $this->elapsed($start), "HTTP {$response->status()}");
                return false;
            }

            $lines = array_filter(explode("\n", $response->body()));
            // Get last lines (most recent) and find matching date
            $recent = array_slice($lines, -50);
            $found = false;

            foreach (array_reverse($recent) as $line) {
                $row = json_decode($line, true);
                if (!$row) continue;

                $rowDate = $row['date'] ?? ($row['draw_date'] ?? null);
                if (!$rowDate) continue;

                // Normalize date format
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $rowDate)) {
                    $normalizedDate = $rowDate;
                } else {
                    $normalizedDate = Carbon::parse($rowDate)->toDateString();
                }

                if ($normalizedDate !== $date) continue;

                $parsed = $this->parseGithubRow($game, $row, $normalizedDate);
                if ($parsed) {
                    $this->saveDraw($game, $parsed);
                    $found = true;
                    if ($game !== 'keno') break; // Keno has multiple periods per day
                }
            }

            $elapsed = $this->elapsed($start);
            if ($found) {
                $this->logScrape('github', $url, 'success', $elapsed);
                return true;
            }

            $this->logScrape('github', $url, 'failed', $elapsed, "No data for {$date}");
            return false;

        } catch (\Exception $e) {
            $this->logScrape('github', $url, 'failed', $this->elapsed($start), $e->getMessage());
            Log::error("XosoMeScraper GitHub failed: {$game}", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Parse xoso.me HTML for Mega/Power/Max3D.
     * Structure: numbers in table cells, date in header "thứ X ngày DD-MM-YYYY"
     */
    private function parseXosoMe(string $game, string $html, string $targetDate): ?array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new \DOMXPath($dom);

        if (in_array($game, ['mega645', 'power655'])) {
            return $this->parsePowerMega($xpath, $game, $targetDate);
        }

        return $this->parseMax3d($xpath, $game, $targetDate);
    }

    private function parsePowerMega(\DOMXPath $xpath, string $game, string $targetDate): ?array
    {
        $headers = $xpath->query("//h2[contains(., 'ngày')]");

        foreach ($headers as $header) {
            $text = $header->textContent;
            if (!preg_match('/ngày\s+(\d{1,2})-(\d{1,2})-(\d{4})/', $text, $m)) continue;

            $drawDate = sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
            if ($drawDate !== $targetDate) continue;

            // Parent div contains h2 + table.data with numbers
            $container = $header->parentNode;
            $iTags = $xpath->query('.//table[contains(@class,"data")][1]//tr[1]/td/i', $container);

            $numbers = [];
            foreach ($iTags as $iTag) {
                $num = trim($iTag->textContent);
                if (preg_match('/^\d{2}$/', $num)) {
                    $numbers[] = $num;
                }
            }

            if (empty($numbers)) continue;

            $jackpotData = [];
            $containerText = $container->textContent;
            if (preg_match('/Jackpot\s*1[^0-9]*([0-9][0-9.,]+)/u', $containerText, $jm)) {
                $jackpotData['jackpot1'] = (int) str_replace(['.', ',', ' '], '', $jm[1]);
            }
            if (preg_match('/Jackpot\s*2[^0-9]*([0-9][0-9.,]+)/u', $containerText, $jm2)) {
                $jackpotData['jackpot2'] = (int) str_replace(['.', ',', ' '], '', $jm2[1]);
            }

            return [
                'date'         => $drawDate,
                'draw_id'      => null,
                'period'       => null,
                'draw_time'    => config('lottery.draw_times.vietlot'),
                'prize_data'   => ['numbers' => $numbers],
                'jackpot_data' => $jackpotData ?: null,
            ];
        }

        return null;
    }

    private function parseMax3d(\DOMXPath $xpath, string $game, string $targetDate): ?array
    {
        $headers = $xpath->query("//h2[contains(., 'Max 3D')]");

        foreach ($headers as $header) {
            $text = $header->textContent;
            if (!preg_match('/ngày\s+(\d{1,2})-(\d{1,2})-(\d{4})/', $text, $m)) continue;

            $drawDate = sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
            if ($drawDate !== $targetDate) continue;

            $parent = $header->parentNode;
            $numbers = [];

            // Max3D numbers are 3-digit
            if (preg_match_all('/\b(\d{3})\b/', $parent->textContent, $allM)) {
                $numbers = array_slice(array_unique($allM[1]), 0, 20);
            }

            if (empty($numbers)) continue;

            return [
                'date'       => $drawDate,
                'draw_id'    => null,
                'period'     => null,
                'draw_time'  => config('lottery.draw_times.vietlot'),
                'prize_data' => ['numbers' => $numbers],
            ];
        }

        return null;
    }

    private function parseGithubRow(string $game, array $row, string $date): ?array
    {
        if ($game === 'keno') {
            $numbers = $row['result'] ?? [];
            if (empty($numbers)) return null;

            return [
                'date'       => $date,
                'draw_id'    => $row['id'] ?? null,
                'period'     => $row['id'] ?? null,
                'draw_time'  => null,
                'prize_data' => ['numbers' => array_map('strval', $numbers)],
            ];
        }

        // Power/Mega/Max3D
        $numbers = $row['result'] ?? [];
        if (empty($numbers)) return null;

        return [
            'date'       => $date,
            'draw_id'    => $row['id'] ?? null,
            'period'     => null,
            'draw_time'  => config('lottery.draw_times.vietlot'),
            'prize_data' => ['numbers' => array_map('strval', $numbers)],
        ];
    }

    private function saveDraw(string $game, array $parsed): void
    {
        $drawKey = $game === 'keno'
            ? ['type' => 'vietlot', 'game' => $game, 'date' => $parsed['date'], 'period' => $parsed['period']]
            : ['type' => 'vietlot', 'game' => $game, 'date' => $parsed['date']];

        $draw = LotteryDraw::updateOrCreate($drawKey, [
            'draw_time'  => $parsed['draw_time'] ?? config('lottery.draw_times.vietlot'),
            'draw_id'    => $parsed['draw_id'],
            'period'     => $parsed['period'],
            'status'     => 'completed',
            'source'     => 'xoso.me',
            'scraped_at' => now(),
        ]);

        $resultData = ['prize_data' => $parsed['prize_data']];
        if (!empty($parsed['jackpot_data'])) {
            $resultData['jackpot_data'] = $parsed['jackpot_data'];
        }

        LotteryResult::updateOrCreate(
            ['draw_id' => $draw->id, 'province_id' => null],
            $resultData
        );
    }

    private function logScrape(string $source, string $url, string $status, int $ms, ?string $error = null): void
    {
        LotteryScrapeLog::create([
            'source'           => $source,
            'url'              => $url,
            'status'           => $status,
            'response_time_ms' => $ms,
            'error_message'    => $error,
            'created_at'       => now(),
        ]);
    }

    private function elapsed(float $start): int
    {
        return (int) ((microtime(true) - $start) * 1000);
    }
}
