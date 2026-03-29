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
        'mega645'   => '/vi/trung-thuong/ket-qua-trung-thuong/winning-number-645',
        'power655'  => '/vi/trung-thuong/ket-qua-trung-thuong/winning-number-655',
        'max3d'     => '/vi/trung-thuong/ket-qua-trung-thuong/winning-number-max-3D',
        'max3d_pro' => '/vi/trung-thuong/ket-qua-trung-thuong/winning-number-max-3Dpro',
        'keno'      => '/vi/trung-thuong/ket-qua-trung-thuong/winning-number-keno',
    ];

    public function __construct()
    {
        $this->http = new Client([
            'timeout'  => config('lottery.scrape.timeout'),
            'base_uri' => config('lottery.scrape.sources.vietlot'),
            'headers'  => [
                'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
                'Accept'          => 'text/html,application/xhtml+xml',
                'Accept-Language' => 'vi-VN,vi;q=0.9',
            ],
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
     * Keno: bảng table.table-hover với cột Ngày/Kỳ, Kết quả (span.bong_tron.small)
     * Mega/Power/Max3D: bảng với span.bong_tron chứa số
     */
    private function parseByGame(string $game, string $html): ?array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new \DOMXPath($dom);

        if ($game === 'keno') {
            return $this->parseKeno($xpath);
        }

        return $this->parseLottoGame($xpath, $game);
    }

    /**
     * Parse Keno — lấy kỳ mới nhất từ bảng kết quả.
     * HTML: <tr> → <td>date + #period</td> <td>span.bong_tron.small</td> ...
     */
    private function parseKeno(\DOMXPath $xpath): ?array
    {
        // Lấy row đầu tiên (kỳ mới nhất)
        $rows = $xpath->query("//div[contains(@class,'doso_keno_output_nd')]//table//tr[td]");
        if ($rows->length < 2) return null; // skip header row

        $row = $rows->item(1); // first data row
        $cells = $xpath->query('.//td', $row);
        if ($cells->length < 2) return null;

        // Cell 0: date + period
        $dateCell = $cells->item(0);
        $links = $xpath->query('.//a', $dateCell);
        $date = null;
        $period = null;
        $drawId = null;

        foreach ($links as $link) {
            $text = trim($link->textContent);
            if (preg_match('#(\d{2}/\d{2}/\d{4})#', $text, $m)) {
                $date = Carbon::createFromFormat('d/m/Y', $m[1])->toDateString();
            }
            if (preg_match('/#(\d+)/', $text, $m)) {
                $drawId = $m[1];
                $period = $m[1];
            }
        }

        if (!$date) return null;

        // Cell 1: numbers in span.bong_tron.small
        $numberSpans = $xpath->query('.//span[contains(@class,"bong_tron")]', $cells->item(1));
        $numbers = [];
        foreach ($numberSpans as $span) {
            $num = trim($span->textContent);
            if (preg_match('/^\d+$/', $num)) {
                $numbers[] = $num;
            }
        }

        if (empty($numbers)) return null;

        // Cell 2/3: chẵn/lẻ, lớn/nhỏ (stats)
        $evenOdd = $cells->length > 2 ? trim($cells->item(2)->textContent) : null;
        $bigSmall = $cells->length > 3 ? trim($cells->item(3)->textContent) : null;

        return [
            'date'       => $date,
            'draw_id'    => $drawId,
            'period'     => $period,
            'draw_time'  => null,
            'prize_data' => ['numbers' => $numbers],
            'stats_data' => [
                'even_odd'  => $evenOdd,
                'big_small' => $bigSmall,
            ],
        ];
    }

    /**
     * Parse Mega 6/45, Power 6/55, Max 3D, Max 3D Pro.
     * HTML: bảng với <td> chứa date, <td> chứa span.bong_tron với số.
     */
    private function parseLottoGame(\DOMXPath $xpath, string $game): ?array
    {
        // Tìm row đầu tiên có kết quả (span.bong_tron với số)
        $rows = $xpath->query("//table[contains(@class,'table')]//tr[td]");

        foreach ($rows as $row) {
            $cells = $xpath->query('.//td', $row);
            if ($cells->length < 2) continue;

            // Tìm cell chứa date
            $date = null;
            $drawId = null;
            foreach ($cells as $cell) {
                $text = trim($cell->textContent);
                if (preg_match('#(\d{2}/\d{2}/\d{4})#', $text, $m)) {
                    $date = Carbon::createFromFormat('d/m/Y', $m[1])->toDateString();
                }
                // Draw ID từ link
                $links = $xpath->query('.//a[contains(@href,"view-detail")]', $cell);
                if ($links->length > 0) {
                    $href = $links->item(0)->getAttribute('href');
                    if (preg_match('/id=(\d+)/', $href, $m)) {
                        $drawId = $m[1];
                    }
                }
            }

            // Tìm numbers từ span.bong_tron
            $numberSpans = $xpath->query('.//span[contains(@class,"bong_tron")]', $row);
            $numbers = [];
            foreach ($numberSpans as $span) {
                $num = trim($span->textContent);
                if (preg_match('/^\d+$/', $num)) {
                    $numbers[] = $num;
                }
            }

            if (!$date || empty($numbers)) continue;

            return [
                'date'       => $date,
                'draw_id'    => $drawId,
                'draw_time'  => config('lottery.draw_times.vietlot'),
                'prize_data' => ['numbers' => $numbers],
            ];
        }

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
