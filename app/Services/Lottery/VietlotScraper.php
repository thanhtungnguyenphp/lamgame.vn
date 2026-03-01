<?php

namespace App\Services\Lottery;

use App\Models\LotteryDraw;
use App\Models\LotteryResult;
use App\Models\LotteryScrapeLog;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class VietlotScraper
{
    private Client $http;

    private array $urls = [
        'mega645'   => '/vi/trung-thuong/ket-qua-trung-thuong/mega-645',
        'power655'  => '/vi/trung-thuong/ket-qua-trung-thuong/power-655',
        'max3d'     => '/vi/trung-thuong/ket-qua-trung-thuong/max-3d',
        'max3d_pro' => '/vi/trung-thuong/ket-qua-trung-thuong/max-3d-pro',
        'keno'      => '/vi/trung-thuong/ket-qua-trung-thuong/winning-number-702',
    ];

    public function __construct()
    {
        $this->http = new Client([
            'timeout'  => config('lottery.scrape.timeout'),
            'base_uri' => config('lottery.scrape.sources.vietlot'),
            'headers'  => ['User-Agent' => config('lottery.scrape.user_agent')],
        ]);
    }

    /**
     * Scrape kết quả Vietlot cho 1 game.
     */
    public function scrape(string $game, ?string $date = null): bool
    {
        $date = $date ?: Carbon::today()->toDateString();
        $path = $this->urls[$game] ?? null;
        if (!$path) return false;

        $url = config('lottery.scrape.sources.vietlot') . $path;
        $start = microtime(true);

        try {
            $response = $this->http->get($path);
            $html = (string) $response->getBody();
            $elapsed = (int) ((microtime(true) - $start) * 1000);

            $parsed = $this->parseByGame($game, $html);

            if (!$parsed) {
                $this->logScrape('vietlott.vn', $url, 'failed', $elapsed, 'No results parsed');
                return false;
            }

            // Upsert draw
            $drawData = [
                'type'   => 'vietlot',
                'game'   => $game,
                'date'   => $parsed['date'] ?? $date,
                'region' => null,
            ];
            if ($game === 'keno' && isset($parsed['period'])) {
                $drawData['period'] = $parsed['period'];
            }

            $draw = LotteryDraw::updateOrCreate(
                $game === 'keno'
                    ? ['type' => 'vietlot', 'game' => $game, 'date' => $drawData['date'], 'period' => $drawData['period'] ?? null]
                    : ['type' => 'vietlot', 'game' => $game, 'date' => $drawData['date']],
                [
                    'draw_time'  => $parsed['draw_time'] ?? config('lottery.draw_times.vietlot'),
                    'draw_id'    => $parsed['draw_id'] ?? null,
                    'period'     => $parsed['period'] ?? null,
                    'status'     => 'completed',
                    'source'     => 'vietlott.vn',
                    'scraped_at' => now(),
                ]
            );

            // Save result
            $resultData = ['prize_data' => $parsed['prize_data']];
            if (isset($parsed['jackpot_data'])) $resultData['jackpot_data'] = $parsed['jackpot_data'];
            if (isset($parsed['stats_data']))   $resultData['stats_data'] = $parsed['stats_data'];

            LotteryResult::updateOrCreate(
                ['draw_id' => $draw->id, 'province_id' => null],
                $resultData
            );

            $this->logScrape('vietlott.vn', $url, 'success', $elapsed);
            return true;

        } catch (\Exception $e) {
            $elapsed = (int) ((microtime(true) - $start) * 1000);
            $this->logScrape('vietlott.vn', $url, 'failed', $elapsed, $e->getMessage());
            Log::error("VietlotScraper failed: {$game} {$date}", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Parse HTML theo từng game.
     * NOTE: Skeleton — cần điều chỉnh selectors khi test với HTML thật.
     * vietlott.vn có thể dùng AJAX → kiểm tra XHR endpoint trước.
     */
    private function parseByGame(string $game, string $html): ?array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new \DOMXPath($dom);

        // TODO: Implement actual parsing per game based on real HTML structure.
        // Mỗi game có cấu trúc HTML khác nhau trên vietlott.vn.
        // Cần inspect thực tế và điều chỉnh.
        //
        // Return format:
        // [
        //   'date'         => '2026-02-28',
        //   'draw_id'      => '01085',
        //   'draw_time'    => '18:00',
        //   'period'       => '256',        // keno only
        //   'prize_data'   => [...],
        //   'jackpot_data' => [...],        // mega/power only
        //   'stats_data'   => [...],        // keno only
        // ]

        Log::info("VietlotScraper::parseByGame - {$game} parse not yet implemented, needs real HTML inspection");
        return null;
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
}
