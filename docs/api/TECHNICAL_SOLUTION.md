# Tài Liệu Kỹ Thuật & Giải Pháp — Lotto Live API trên Laravel LamGame

> Ngày cập nhật: 2026-02-28
> Phiên bản: 2.0 (Laravel-based)

---

## 1. Tổng Quan Giải Pháp

### 1.1 Chiến lược

Tận dụng **toàn bộ hạ tầng Laravel có sẵn** của dự án `lamgame.vn` (Bagisto-based) làm core API, thay vì xây dựng Go service riêng. Điều này giúp:

- Dùng chung hệ thống auth (Sanctum), middleware, rate limiting
- Dùng chung database MySQL, Redis cache, queue system
- Dùng chung Docker deployment pipeline
- Không cần maintain thêm 1 tech stack riêng

### 1.2 Hạ tầng có sẵn được tận dụng

| Thành phần | Có sẵn | Tận dụng cho Lottery |
|---|---|---|
| Laravel 11 + PHP 8.2 | ✅ | Framework core |
| Sanctum Auth | ✅ | API authentication |
| MySQL + Eloquent ORM | ✅ | Lưu trữ kết quả xổ số |
| Redis (predis) | ✅ | Cache kết quả realtime |
| Guzzle HTTP | ✅ | Scrape nguồn dữ liệu |
| Queue system | ✅ | Background scrape jobs |
| Rate limiting (throttle) | ✅ | Giới hạn request |
| Spatie Response Cache | ✅ | Cache HTTP response |
| Laravel Octane | ✅ | High-performance serving |
| Docker deployment | ✅ | Deploy chung |

### 1.3 Kiến trúc

```
┌─────────────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                                 │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐              │
│  │ Flutter App   │  │  Web/Vue.js  │  │ 3rd Party    │              │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘              │
└─────────┼──────────────────┼──────────────────┼─────────────────────┘
          │                  │                  │
          ▼                  ▼                  ▼
┌─────────────────────────────────────────────────────────────────────┐
│              LAMGAME LARAVEL API (Existing Infrastructure)          │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────┐       │
│  │ Existing: Auth, Jobs, Banner, Blog, Forum, Seller APIs  │       │
│  └─────────────────────────────────────────────────────────┘       │
│  ┌─────────────────────────────────────────────────────────┐       │
│  │ NEW: Lottery Module                                      │       │
│  │  ├── Controllers (5 endpoints)                           │       │
│  │  ├── Models (Eloquent)                                   │       │
│  │  ├── Services (Scraper + Business Logic)                 │       │
│  │  ├── Jobs (Scheduled scraping)                           │       │
│  │  └── Resources (JSON transformation)                     │       │
│  └─────────────────────────────────────────────────────────┘       │
│                          │                                          │
│              ┌───────────┴───────────┐                              │
│              ▼                       ▼                              │
│       ┌────────────┐         ┌────────────┐                        │
│       │   MySQL    │         │   Redis    │                        │
│       │ (persist)  │         │  (cache)   │                        │
│       └────────────┘         └────────────┘                        │
└─────────────────────────────────────────────────────────────────────┘
          │ (Scheduled Jobs)
          ▼
┌─────────────────────────────────────────────────────────────────────┐
│  External Sources: xoso.com.vn | vietlott.vn | backup sources      │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 2. Thiết Kế Database

### 2.1 Tổng quan tables mới

```
lottery_draws              -- Kỳ quay (truyền thống + vietlot)
lottery_results            -- Kết quả chi tiết từng đài/game
lottery_provinces          -- Danh sách tỉnh/đài (static)
lottery_schedules          -- Lịch quay theo thứ (static)
lottery_scrape_logs        -- Log scrape để monitor
```

### 2.2 ERD

```
lottery_provinces (static)
├── id, code, name, region, sort_order
│
lottery_schedules (static)
├── id, province_id, day_of_week
│
lottery_draws
├── id, type(traditional/vietlot), game, region
├── date, draw_time, draw_id, period
├── status(pending/completed/error)
├── source, scraped_at
│
└── lottery_results
    ├── id, draw_id
    ├── province_id (nullable, cho truyền thống)
    ├── prize_data (JSON)
    ├── jackpot_data (JSON, nullable, cho vietlot)
    ├── stats_data (JSON, nullable, cho keno)
```

### 2.3 Chi tiết từng table

#### `lottery_provinces`

Dữ liệu tĩnh — 36 đài truyền thống.

```sql
CREATE TABLE lottery_provinces (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code        VARCHAR(10) NOT NULL UNIQUE,     -- 'VL', 'BD', 'HCM'...
    name        VARCHAR(100) NOT NULL,            -- 'Vĩnh Long', 'Bình Dương'...
    region      ENUM('mien-nam','mien-trung','mien-bac') NOT NULL,
    sort_order  TINYINT UNSIGNED DEFAULT 0,
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL,

    INDEX idx_region (region)
);
```

#### `lottery_schedules`

Lịch quay tĩnh — đài nào quay thứ mấy.

```sql
CREATE TABLE lottery_schedules (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    province_id BIGINT UNSIGNED NOT NULL,
    day_of_week TINYINT UNSIGNED NOT NULL,        -- 1=Monday...7=Sunday
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL,

    FOREIGN KEY (province_id) REFERENCES lottery_provinces(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_province_day (province_id, day_of_week),
    INDEX idx_day (day_of_week)
);
```

#### `lottery_draws`

Mỗi kỳ quay = 1 record. Bao gồm cả truyền thống và vietlot.

```sql
CREATE TABLE lottery_draws (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type        ENUM('traditional','vietlot') NOT NULL,
    game        VARCHAR(20) DEFAULT NULL,          -- NULL cho traditional, 'mega645'/'power655'/'max3d'/'max3d_pro'/'keno' cho vietlot
    region      VARCHAR(20) DEFAULT NULL,          -- 'mien-nam'/'mien-trung'/'mien-bac' cho traditional, NULL cho vietlot
    date        DATE NOT NULL,
    draw_time   VARCHAR(5) DEFAULT NULL,           -- '16:15', '18:00'
    draw_id     VARCHAR(20) DEFAULT NULL,          -- Mã kỳ quay vietlot
    period      VARCHAR(10) DEFAULT NULL,          -- Kỳ Keno
    status      ENUM('pending','completed','error') DEFAULT 'pending',
    source      VARCHAR(50) DEFAULT NULL,          -- 'xoso.com.vn', 'vietlott.vn'
    scraped_at  TIMESTAMP NULL,
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL,

    INDEX idx_type_date (type, date),
    INDEX idx_game_date (game, date),
    INDEX idx_region_date (region, date),
    INDEX idx_status (status),
    UNIQUE KEY uniq_traditional (type, region, date, game),
    UNIQUE KEY uniq_vietlot_keno (game, date, period)
);
```

#### `lottery_results`

Kết quả chi tiết — mỗi đài/game 1 record per draw.

```sql
CREATE TABLE lottery_results (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    draw_id     BIGINT UNSIGNED NOT NULL,
    province_id BIGINT UNSIGNED DEFAULT NULL,      -- Cho truyền thống, NULL cho vietlot
    prize_data  JSON NOT NULL,                     -- Cấu trúc giải thưởng (xem bên dưới)
    jackpot_data JSON DEFAULT NULL,                -- Jackpot info cho vietlot
    stats_data  JSON DEFAULT NULL,                 -- Stats cho Keno
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL,

    FOREIGN KEY (draw_id) REFERENCES lottery_draws(id) ON DELETE CASCADE,
    FOREIGN KEY (province_id) REFERENCES lottery_provinces(id) ON DELETE SET NULL,
    INDEX idx_draw (draw_id),
    INDEX idx_province (province_id)
);
```

#### `lottery_scrape_logs`

Monitor scraping health.

```sql
CREATE TABLE lottery_scrape_logs (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    source      VARCHAR(50) NOT NULL,
    url         VARCHAR(500) NOT NULL,
    status      ENUM('success','failed') NOT NULL,
    response_time_ms INT UNSIGNED DEFAULT NULL,
    error_message TEXT DEFAULT NULL,
    created_at  TIMESTAMP NULL,

    INDEX idx_source_status (source, status),
    INDEX idx_created (created_at)
);
```

### 2.4 JSON Data Structures

#### `prize_data` — XS Truyền thống

```json
{
  "giai_db": ["123456"],
  "giai_1":  ["12345"],
  "giai_2":  ["12345"],
  "giai_3":  ["12345", "67890"],
  "giai_4":  ["12345", "67890", "11111", "22222", "33333", "44444", "55555"],
  "giai_5":  ["1234"],
  "giai_6":  ["1234", "5678", "9012"],
  "giai_7":  ["123"],
  "giai_8":  ["12"]
}
```

#### `prize_data` — Mega 6/45

```json
{
  "numbers": [3, 12, 25, 33, 41, 45],
  "prizes": [
    {"name": "Jackpot",   "match": "6 số",  "value": 15234567890, "winners": 0},
    {"name": "Giải Nhất", "match": "5 số",  "value": 10000000,    "winners": 5},
    {"name": "Giải Nhì",  "match": "4 số",  "value": 300000,      "winners": 142},
    {"name": "Giải Ba",   "match": "3 số",  "value": 30000,       "winners": 2891}
  ]
}
```

#### `prize_data` — Power 6/55

```json
{
  "numbers": [5, 11, 22, 38, 44, 55],
  "power_number": 3,
  "prizes": [
    {"name": "Jackpot 1", "match": "6 số",        "value": 52000000000, "winners": 0},
    {"name": "Jackpot 2", "match": "5 số + Power", "value": 5800000000,  "winners": 0},
    {"name": "Giải Nhất", "match": "5 số",         "value": 40000000,    "winners": 3},
    {"name": "Giải Nhì",  "match": "4 số",         "value": 500000,      "winners": 98},
    {"name": "Giải Ba",   "match": "3 số",         "value": 50000,       "winners": 1820}
  ]
}
```

#### `prize_data` — Max 3D

```json
{
  "numbers_a": "385",
  "numbers_b": "712",
  "prizes": [
    {"name": "Giải Đặc biệt", "match": "Trùng 2 bộ số đúng vị trí", "value": 1000000000, "winners": 0},
    {"name": "Giải Nhất",      "match": "Trùng 2 bộ số bất kỳ",      "value": 400000,     "winners": 12}
  ]
}
```

#### `prize_data` — Max 3D Pro

```json
{
  "numbers": [["385","712"],["100","896"],["633","383"]],
  "prizes": [
    {"name": "Giải Đặc biệt", "match": "Trùng 2 bộ số cặp 1 đúng vị trí", "value": 1000000000}
  ]
}
```

#### `prize_data` — Keno

```json
{
  "numbers": [2, 5, 8, 11, 15, 19, 22, 28, 33, 37, 41, 44, 48, 52, 55, 60, 65, 70, 74, 80]
}
```

#### `stats_data` — Keno

```json
{
  "total": 856,
  "big_small": "lon",
  "odd_even": "chan",
  "up_down": "tren"
}
```

#### `jackpot_data` — Mega 6/45

```json
{
  "value": 15234567890,
  "value_display": "15.2 tỷ",
  "currency": "VND",
  "winners": 0
}
```

#### `jackpot_data` — Power 6/55

```json
{
  "jackpot_1": {"value": 52000000000, "value_display": "52 tỷ", "winners": 0},
  "jackpot_2": {"value": 5800000000,  "value_display": "5.8 tỷ", "winners": 0}
}
```

---

## 3. Cấu Trúc Code Mới

### 3.1 Files cần tạo

```
app/
├── Http/Controllers/Api/Lottery/
│   ├── LotteryHealthController.php
│   ├── LotteryLatestController.php
│   ├── TraditionalLotteryController.php
│   ├── VietlotController.php
│   └── LotteryScheduleController.php
├── Models/
│   ├── LotteryDraw.php
│   ├── LotteryResult.php
│   ├── LotteryProvince.php
│   ├── LotterySchedule.php
│   └── LotteryScrapeLog.php
├── Services/Lottery/
│   ├── LotteryService.php              -- Business logic chính
│   ├── TraditionalScraper.php          -- Scrape xoso.com.vn
│   └── VietlotScraper.php             -- Scrape vietlott.vn
├── Jobs/
│   ├── ScrapeTraditionalLottery.php    -- Queue job scrape truyền thống
│   └── ScrapeVietlotLottery.php        -- Queue job scrape vietlot
└── Http/Resources/Lottery/
    ├── TraditionalResultResource.php
    ├── VietlotResultResource.php
    └── ScheduleResource.php

database/migrations/
├── 2026_02_28_000001_create_lottery_provinces_table.php
├── 2026_02_28_000002_create_lottery_schedules_table.php
├── 2026_02_28_000003_create_lottery_draws_table.php
├── 2026_02_28_000004_create_lottery_results_table.php
├── 2026_02_28_000005_create_lottery_scrape_logs_table.php
└── 2026_02_28_000006_seed_lottery_provinces_and_schedules.php

routes/api/
└── lottery.php

config/
└── lottery.php
```

### 3.2 Routes (`routes/api/lottery.php`)

```php
Route::prefix('v1/lottery')->name('api.lottery.')->middleware('throttle:60,1')->group(function () {
    Route::get('/health',       [LotteryHealthController::class, 'index']);
    Route::get('/latest',       [LotteryLatestController::class, 'index']);
    Route::get('/traditional',  [TraditionalLotteryController::class, 'index']);
    Route::get('/vietlot',      [VietlotController::class, 'index']);
    Route::get('/schedule',     [LotteryScheduleController::class, 'index']);
});
```

Thêm vào `routes/api.php`:
```php
require __DIR__ . '/api/lottery.php';
```

### 3.3 Config (`config/lottery.php`)

```php
return [
    'cache' => [
        'ttl_live'     => env('LOTTERY_CACHE_TTL_LIVE', 300),      // 5 phút
        'ttl_done'     => env('LOTTERY_CACHE_TTL_DONE', 3600),     // 1 giờ
        'ttl_history'  => env('LOTTERY_CACHE_TTL_HISTORY', 86400), // 24 giờ
        'ttl_keno'     => env('LOTTERY_CACHE_TTL_KENO', 120),      // 2 phút
        'ttl_schedule' => env('LOTTERY_CACHE_TTL_SCHEDULE', 604800),
    ],
    'scrape' => [
        'timeout'    => env('LOTTERY_SCRAPE_TIMEOUT', 10),
        'user_agent' => env('LOTTERY_SCRAPE_UA', 'Mozilla/5.0 (compatible; LottoLiveBot/1.0)'),
        'sources' => [
            'traditional' => 'https://xoso.com.vn',
            'vietlot'     => 'https://vietlott.vn',
        ],
    ],
    'draw_times' => [
        'mien-nam'   => '16:15',
        'mien-trung' => '17:15',
        'mien-bac'   => '18:15',
        'vietlot'    => '18:00',
        'keno_start' => '06:00',
        'keno_end'   => '21:55',
        'keno_interval' => 10,
    ],
];
```

---

## 4. Flow Xử Lý

### 4.1 Request Flow

```
Request
  → Middleware (throttle, ApiAuthentication, CORS)
    → Controller
      → Redis Cache check
        → HIT: return cached response
        → MISS: LotteryService → query DB
          → DB có data: return + cache
          → DB không có: dispatch ScrapeJob (sync/async)
            → Scraper → parse HTML → save DB → cache → return
```

### 4.2 Scheduled Scraping (Proactive)

Thay vì chờ request mới scrape, dùng Laravel Scheduler chủ động scrape:

```
# app/Console/Kernel.php schedule()

// Truyền thống — scrape sau giờ quay
$schedule->job(new ScrapeTraditionalLottery('mien-nam'))->dailyAt('16:20');
$schedule->job(new ScrapeTraditionalLottery('mien-trung'))->dailyAt('17:20');
$schedule->job(new ScrapeTraditionalLottery('mien-bac'))->dailyAt('18:20');

// Vietlot — scrape sau 18:00
$schedule->job(new ScrapeVietlotLottery)->dailyAt('18:05');

// Keno — mỗi 10 phút trong khung giờ
$schedule->job(new ScrapeVietlotLottery('keno'))->everyTenMinutes()
    ->between('6:00', '22:00');
```

### 4.3 Cache Strategy (Redis)

```php
// Cache key patterns
"lottery:traditional:{region}:{date}"          // TTL: 5min (live) / 1h (done)
"lottery:traditional:{region}:{date}:{code}"   // TTL: same
"lottery:vietlot:{game}:{date}"                // TTL: 5min (live) / 1h (done)
"lottery:vietlot:keno:{date}:{period}"         // TTL: 2min
"lottery:latest"                                // TTL: 5min
"lottery:schedule:{date}"                       // TTL: 7 days
```

---

## 5. API Endpoints (giữ nguyên spec)

Tất cả endpoints giữ nguyên response format như tài liệu gốc (`01`–`04`):

| Endpoint | Controller | Mô tả |
|---|---|---|
| `GET /api/v1/lottery/health` | `LotteryHealthController` | Health check + source status |
| `GET /api/v1/lottery/latest` | `LotteryLatestController` | Tổng hợp kết quả mới nhất |
| `GET /api/v1/lottery/traditional?region=&date=&province=` | `TraditionalLotteryController` | XS truyền thống |
| `GET /api/v1/lottery/vietlot?game=&date=&period=` | `VietlotController` | Vietlot 5 games |
| `GET /api/v1/lottery/schedule?date=&type=` | `LotteryScheduleController` | Lịch quay số |

Response format chuẩn giữ nguyên:
```json
{
  "status": "ok",
  "data": { ... },
  "meta": { "cached": true, "fetched_at": "..." }
}
```

---

## 6. Kế Hoạch Triển Khai

### Phase 1: Database & Models (1–2 ngày)

- [ ] Tạo 5 migration files + seeder provinces/schedules
- [ ] Tạo 5 Eloquent Models với relationships
- [ ] Run migrate + seed

### Phase 2: Scraper Services (2–3 ngày)

- [ ] `TraditionalScraper` — parse xoso.com.vn bằng Guzzle + DOM parser
- [ ] `VietlotScraper` — parse vietlott.vn (XHR endpoint hoặc HTML)
- [ ] `LotteryService` — business logic, cache, fallback
- [ ] Unit test scrapers

### Phase 3: API Controllers & Routes (1–2 ngày)

- [ ] 5 Controllers + JSON Resources
- [ ] Route file `routes/api/lottery.php`
- [ ] Config file `config/lottery.php`
- [ ] Test endpoints bằng curl/Postman

### Phase 4: Scheduling & Production (1 ngày)

- [ ] Scheduled jobs trong `Kernel.php`
- [ ] Queue worker config cho scrape jobs
- [ ] Thêm env variables vào `.env`
- [ ] Deploy

**Tổng ước tính: 5–8 ngày**

---

## 7. Rủi Ro & Giải Pháp

| Rủi ro | Giải pháp |
|---|---|
| Nguồn scrape thay đổi HTML | Nhiều nguồn backup, alert khi parse fail qua `lottery_scrape_logs` |
| Nguồn block IP | Rotate User-Agent, rate limit scrape, proxy nếu cần |
| Keno volume lớn (~96 kỳ/ngày) | Redis cache 2 phút, chỉ lưu DB kỳ mới nhất + theo ngày |
| vietlott.vn dùng JS render | Ưu tiên tìm XHR/AJAX endpoint, fallback dùng `symfony/panther` |
| DB size tăng theo thời gian | Partition theo tháng, archive data cũ > 1 năm |

---

## 8. Environment Variables bổ sung

Thêm vào `.env`:

```env
# Lottery API Config
LOTTERY_CACHE_TTL_LIVE=300
LOTTERY_CACHE_TTL_DONE=3600
LOTTERY_CACHE_TTL_HISTORY=86400
LOTTERY_CACHE_TTL_KENO=120
LOTTERY_CACHE_TTL_SCHEDULE=604800
LOTTERY_SCRAPE_TIMEOUT=10
LOTTERY_SCRAPE_UA="Mozilla/5.0 (compatible; LottoLiveBot/1.0)"

# Đổi cache driver sang Redis (khuyến nghị)
CACHE_STORE=redis
```
