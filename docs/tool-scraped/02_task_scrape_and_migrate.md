# Task: Scrape & Tạo Migration Data Xổ Số

## Mục đích

Hướng dẫn từng bước để scrape kết quả xổ số và tạo migration seed data cho deploy.

---

## Task 1: Scrape kết quả hàng ngày

### Điều kiện

- Miền Nam: sau **16:15**
- Miền Trung: sau **17:15**
- Miền Bắc: sau **18:15**

### Lệnh

```bash
# Scrape hôm nay (tất cả miền)
docker exec lamgame-php php artisan lottery:scrape

# Scrape 1 miền cụ thể
docker exec lamgame-php php artisan lottery:scrape --region=mien-nam

# Scrape ngày cụ thể
docker exec lamgame-php php artisan lottery:scrape --region=mien-nam --date=2026-03-04

# Scrape nhiều ngày (bù data thiếu)
for d in 2026-03-01 2026-03-02 2026-03-03; do
  docker exec lamgame-php php artisan lottery:scrape --region=mien-nam --date=$d
done
```

### Verify

```bash
# Kiểm tra data trong DB
docker exec shared-mysql mysql -ulamgame -plamgame lamgame -e "
  SELECT d.date, d.region, COUNT(r.id) as provinces
  FROM lottery_draws d
  LEFT JOIN lottery_results r ON r.draw_id = d.id
  GROUP BY d.id ORDER BY d.date DESC LIMIT 10;"

# Test API
docker exec lamgame-php curl -s 'http://lamgame-web/api/v1/lottery/traditional?region=mien-nam' \
  -H 'Accept: application/json' | python3 -m json.tool
```

---

## Task 2: Tạo migration seed data cho deploy

### Bước 1 — Export data từ DB ra JSON

```bash
# Thay DATE và REGION phù hợp
docker exec lamgame-php php -r '
$pdo = new PDO("mysql:host=shared-mysql;dbname=lamgame", "lamgame", "lamgame");

$draws = $pdo->query("
    SELECT id, type, region, date, draw_time, status, source
    FROM lottery_draws WHERE region = \"mien-nam\" AND date = \"DATE_HERE\"
    ORDER BY date
")->fetchAll(PDO::FETCH_ASSOC);

$results = $pdo->query("
    SELECT r.draw_id, p.code as province_code, r.prize_data
    FROM lottery_results r
    JOIN lottery_draws d ON d.id = r.draw_id
    JOIN lottery_provinces p ON p.id = r.province_id
    WHERE d.region = \"mien-nam\" AND d.date = \"DATE_HERE\"
    ORDER BY p.code
")->fetchAll(PDO::FETCH_ASSOC);

$data = [];
foreach ($draws as $d) {
    $entry = [
        "type" => $d["type"], "region" => $d["region"], "date" => $d["date"],
        "draw_time" => $d["draw_time"], "status" => $d["status"], "source" => $d["source"],
        "results" => [],
    ];
    foreach ($results as $r) {
        if ($r["draw_id"] == $d["id"]) {
            $entry["results"][] = [
                "province_code" => $r["province_code"],
                "prize_data" => json_decode($r["prize_data"], true),
            ];
        }
    }
    $data[] = $entry;
}

file_put_contents("/var/www/html/database/seeders/data/lottery_mien_nam_YYYYMMDD.json",
    json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
echo "OK: " . count($data) . " draws";
'
```

### Bước 2 — Tạo migration file

Tạo file `database/migrations/YYYY_MM_DD_HHMMSS_seed_lottery_mien_nam_YYYYMMDD.php`:

```php
<?php

use App\Models\LotteryDraw;
use App\Models\LotteryProvince;
use App\Models\LotteryResult;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $json = file_get_contents(database_path('seeders/data/lottery_mien_nam_YYYYMMDD.json'));
        $draws = json_decode($json, true);
        $provinceMap = LotteryProvince::pluck('id', 'code')->toArray();

        foreach ($draws as $d) {
            $draw = LotteryDraw::updateOrCreate(
                ['type' => $d['type'], 'region' => $d['region'], 'date' => $d['date'], 'game' => null],
                [
                    'draw_time'  => $d['draw_time'],
                    'status'     => $d['status'],
                    'source'     => $d['source'],
                    'scraped_at' => now(),
                ]
            );

            foreach ($d['results'] as $r) {
                $provinceId = $provinceMap[$r['province_code']] ?? null;
                if (!$provinceId) continue;

                LotteryResult::updateOrCreate(
                    ['draw_id' => $draw->id, 'province_id' => $provinceId],
                    ['prize_data' => $r['prize_data']]
                );
            }
        }
    }

    public function down(): void
    {
        LotteryDraw::where('type', 'traditional')
            ->where('region', 'mien-nam')
            ->where('date', 'YYYY-MM-DD')
            ->each(fn ($draw) => $draw->delete());
    }
};
```

### Bước 3 — Test

```bash
# Chạy migration
docker exec lamgame-php php artisan migrate

# Test rollback
docker exec lamgame-php php artisan migrate:rollback --step=1

# Chạy lại
docker exec lamgame-php php artisan migrate
```

---

## Task 3: Troubleshooting

### Scrape fail — "No results parsed"

**Nguyên nhân phổ biến:**
1. Chưa đến giờ quay → chờ sau giờ quay
2. HTML structure thay đổi → kiểm tra selectors trong `TraditionalScraper`
3. Tên tỉnh mới/khác → thêm alias trong `getProvinceNameToCodeMap()`

**Debug:**

```bash
# Xem scrape log
docker exec shared-mysql mysql -ulamgame -plamgame lamgame -e "
  SELECT url, status, error_message, response_time_ms
  FROM lottery_scrape_logs ORDER BY id DESC LIMIT 5;"

# Test HTML trực tiếp
docker exec lamgame-php php -r "
  \$html = file_get_contents('https://xoso.com.vn/xsmn-04-03-2026.html',
    false, stream_context_create(['http' => ['header' => 'User-Agent: Mozilla/5.0']]));
  echo strlen(\$html) . ' bytes' . PHP_EOL;
  echo substr(\$html, 0, 2000);
"
```

### Province không match

```bash
# Xem tên tỉnh trong DB
docker exec shared-mysql mysql -ulamgame -plamgame lamgame -e "
  SELECT code, name, region FROM lottery_provinces ORDER BY region, name;"
```

Thêm alias mới trong `TraditionalScraper::getProvinceNameToCodeMap()`.

---

## Files liên quan

| File | Vai trò |
|---|---|
| `app/Services/Lottery/TraditionalScraper.php` | Scraper + HTML parser |
| `app/Services/Lottery/LotteryService.php` | Business logic + cache |
| `app/Console/Commands/LotteryScrapeCommand.php` | Artisan command |
| `app/Http/Controllers/Api/Lottery/TraditionalLotteryController.php` | API controller |
| `config/lottery.php` | Config (timeout, sources, draw times, cache TTL) |
| `database/seeders/data/lottery_*.json` | Seed data JSON |
| `database/migrations/*seed_lottery_*.php` | Seed migrations |
