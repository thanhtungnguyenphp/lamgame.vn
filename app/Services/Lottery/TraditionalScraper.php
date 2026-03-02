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
     *
     * Miền Nam/Trung: nhiều province, thứ tự rows: giải 8 → 7 → ... → ĐB
     * Miền Bắc: 1 đài (Hà Nội), thứ tự rows: ĐB → 1 → 2 → ... → 7, không có prize-col header
     */
    private function parseHtml(string $html, string $region): array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new \DOMXPath($dom);

        if ($region === 'mien-bac') {
            return $this->parseMienBac($xpath);
        }

        return $this->parseMienNamTrung($xpath, $region);
    }

    private function parseMienBac(\DOMXPath $xpath): array
    {
        $prizeKeys = ['giai_db', 'giai_1', 'giai_2', 'giai_3', 'giai_4', 'giai_5', 'giai_6', 'giai_7'];
        $rows = $xpath->query("//table[contains(@class,'table-result')]//tr[td]");

        $prizes = [];
        $prizeIndex = 0;

        foreach ($rows as $row) {
            $cell = $xpath->query(".//td[not(contains(@class,'number-prize'))]", $row)->item(0);
            if (!$cell) continue;
            if ($prizeIndex >= count($prizeKeys)) break;

            $text = trim($cell->textContent);
            if ($text === '...' || $text === '') {
                $prizes[$prizeKeys[$prizeIndex]] = [];
            } else {
                $prizes[$prizeKeys[$prizeIndex]] = array_values(array_filter(
                    preg_split('/\s+/', $text),
                    fn ($n) => preg_match('/^\d+$/', $n)
                ));
            }
            $prizeIndex++;
        }

        if (empty(array_filter($prizes))) return [];

        return [['province_code' => 'HN', 'prizes' => $prizes]];
    }

    private function parseMienNamTrung(\DOMXPath $xpath, string $region): array
    {
        $provinceNodes = $xpath->query("//th[contains(@class,'prize-col')]//h3//a");
        $provinceMap = $this->getProvinceNameToCodeMap($region);
        $provinceCodes = [];

        foreach ($provinceNodes as $node) {
            $name = trim($node->textContent);
            $code = $provinceMap[$name] ?? $this->fuzzyMatchProvince($name, $provinceMap);
            if ($code) $provinceCodes[] = $code;
        }

        if (empty($provinceCodes)) return [];

        $numProvinces = count($provinceCodes);
        $prizeKeys = ['giai_8', 'giai_7', 'giai_6', 'giai_5', 'giai_4', 'giai_3', 'giai_2', 'giai_1', 'giai_db'];

        $rows = $xpath->query("//table[contains(@class,'table-result')]//tr[td]");
        $prizeData = array_fill(0, $numProvinces, []);
        $prizeIndex = 0;

        foreach ($rows as $row) {
            if ($prizeIndex >= count($prizeKeys)) break;
            $cells = $xpath->query(".//td", $row);
            if ($cells->length < $numProvinces) continue;

            $key = $prizeKeys[$prizeIndex];

            for ($col = 0; $col < $numProvinces; $col++) {
                $text = trim($cells->item($col)->textContent);
                if ($text === '...' || $text === '') {
                    $prizeData[$col][$key] = [];
                    continue;
                }
                $prizeData[$col][$key] = array_values(array_filter(
                    preg_split('/\s+/', $text),
                    fn ($n) => preg_match('/^\d+$/', $n)
                ));
            }
            $prizeIndex++;
        }

        $results = [];
        foreach ($provinceCodes as $i => $code) {
            if (!empty(array_filter($prizeData[$i]))) {
                $results[] = ['province_code' => $code, 'prizes' => $prizeData[$i]];
            }
        }

        return $results;
    }

    private function getProvinceNameToCodeMap(string $region): array
    {
        $map = LotteryProvince::byRegion($region)->pluck('code', 'name')->toArray();

        // Alias cho tên viết tắt trên xoso.com.vn
        $aliases = [
            'TPHCM' => 'HCM', 'TP.HCM' => 'HCM', 'Hồ Chí Minh' => 'HCM',
            'Bình Dương' => 'BD', 'Vũng Tàu' => 'VT', 'Bạc Liêu' => 'BL',
            'Đắk Lắk' => 'DLK', 'Đắk Nông' => 'DNO',
        ];

        return array_merge($map, $aliases);
    }

    private function fuzzyMatchProvince(string $name, array $map): ?string
    {
        $name = mb_strtolower($name);
        foreach ($map as $provinceName => $code) {
            if (mb_strpos(mb_strtolower($provinceName), $name) !== false
                || mb_strpos($name, mb_strtolower($provinceName)) !== false) {
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
