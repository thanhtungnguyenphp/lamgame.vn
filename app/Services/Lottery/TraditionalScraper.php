<?php

namespace App\Services\Lottery;

use App\Models\LotteryDraw;
use App\Models\LotteryProvince;
use App\Models\LotteryResult;
use App\Models\LotteryScrapeLog;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TraditionalScraper
{
    private Client $http;

    public function __construct()
    {
        $this->http = new Client([
            'timeout' => config('lottery.scrape.timeout'),
            'headers' => ['User-Agent' => config('lottery.scrape.user_agent')],
        ]);
    }

    /**
     * Scrape kết quả XS truyền thống cho 1 miền, 1 ngày.
     */
    public function scrape(string $region, ?string $date = null): bool
    {
        $date = $date ?: Carbon::today()->toDateString();
        $carbon = Carbon::parse($date);
        $dateStr = $carbon->format('d-m-Y');

        $prefixMap = ['mien-nam' => 'xsmn', 'mien-trung' => 'xsmt', 'mien-bac' => 'xsmb'];
        $prefix = $prefixMap[$region] ?? null;
        if (!$prefix) return false;

        $url = config('lottery.scrape.sources.traditional') . "/{$prefix}-{$dateStr}.html";
        $start = microtime(true);

        try {
            $response = $this->http->get($url);
            $html = (string) $response->getBody();
            $elapsed = (int) ((microtime(true) - $start) * 1000);

            $results = $this->parseHtml($html, $region);

            if (empty($results)) {
                $this->logScrape('xoso.com.vn', $url, 'failed', $elapsed, 'No results parsed');
                return false;
            }

            $drawTimes = config('lottery.draw_times');

            // Upsert draw
            $draw = LotteryDraw::updateOrCreate(
                ['type' => 'traditional', 'region' => $region, 'date' => $date, 'game' => null],
                [
                    'draw_time'  => $drawTimes[$region] ?? null,
                    'status'     => 'completed',
                    'source'     => 'xoso.com.vn',
                    'scraped_at' => now(),
                ]
            );

            // Save results per province
            foreach ($results as $item) {
                $province = LotteryProvince::where('code', $item['province_code'])->first();
                if (!$province) continue;

                LotteryResult::updateOrCreate(
                    ['draw_id' => $draw->id, 'province_id' => $province->id],
                    ['prize_data' => $item['prizes']]
                );
            }

            $this->logScrape('xoso.com.vn', $url, 'success', $elapsed);
            return true;

        } catch (\Exception $e) {
            $elapsed = (int) ((microtime(true) - $start) * 1000);
            $this->logScrape('xoso.com.vn', $url, 'failed', $elapsed, $e->getMessage());
            Log::error("TraditionalScraper failed: {$region} {$date}", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Parse HTML từ xoso.com.vn
     * Trả về array of ['province_code' => ..., 'prizes' => [...]]
     *
     * NOTE: Cần cài ext-dom. Logic parse phụ thuộc vào cấu trúc HTML thực tế.
     * Đây là skeleton — cần điều chỉnh selectors khi test với HTML thật.
     */
    private function parseHtml(string $html, string $region): array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new \DOMXPath($dom);

        $results = [];

        // Tìm tên tỉnh từ header columns
        $provinceNodes = $xpath->query("//th[contains(@class,'prize-col')]//h3//a");
        $provinceCodes = [];
        $provinceMap = $this->getProvinceNameToCodeMap($region);

        foreach ($provinceNodes as $node) {
            $name = trim($node->textContent);
            $code = $provinceMap[$name] ?? $this->fuzzyMatchProvince($name, $provinceMap);
            if ($code) {
                $provinceCodes[] = $code;
            }
        }

        if (empty($provinceCodes)) return [];

        // Parse prize rows
        $prizeKeys = ['giai_db', 'giai_1', 'giai_2', 'giai_3', 'giai_4', 'giai_5', 'giai_6', 'giai_7', 'giai_8'];
        $rows = $xpath->query("//table[contains(@class,'result')]//tr");

        $prizeData = array_fill(0, count($provinceCodes), []);
        $prizeIndex = 0;

        foreach ($rows as $row) {
            $cells = $xpath->query(".//td[contains(@class,'number') or @data-loto]", $row);
            if ($cells->length === 0) continue;
            if ($prizeIndex >= count($prizeKeys)) break;

            $key = $prizeKeys[$prizeIndex];
            $colIndex = 0;

            foreach ($cells as $cell) {
                if ($colIndex >= count($provinceCodes)) break;
                $numbers = array_filter(
                    array_map('trim', preg_split('/[\s,]+/', trim($cell->textContent))),
                    fn ($n) => $n !== ''
                );
                $prizeData[$colIndex][$key] = array_values($numbers);
                $colIndex++;
            }

            $prizeIndex++;
        }

        foreach ($provinceCodes as $i => $code) {
            if (!empty($prizeData[$i])) {
                $results[] = [
                    'province_code' => $code,
                    'prizes'        => $prizeData[$i],
                ];
            }
        }

        return $results;
    }

    private function getProvinceNameToCodeMap(string $region): array
    {
        return LotteryProvince::byRegion($region)
            ->pluck('code', 'name')
            ->toArray();
    }

    private function fuzzyMatchProvince(string $name, array $map): ?string
    {
        $name = mb_strtolower($name);
        foreach ($map as $provinceName => $code) {
            if (mb_strpos(mb_strtolower($provinceName), $name) !== false || mb_strpos($name, mb_strtolower($provinceName)) !== false) {
                return $code;
            }
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
