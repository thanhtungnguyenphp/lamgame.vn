# SportPulse — Kế hoạch Crawl & Tổng hợp Data

> Ngày: 2026-03-31
> Branch: `feat/APISportPulse`

---

## 1. Tổng quan

### Data cần crawl

| Loại data | Tần suất | Nguồn | Ưu tiên |
|-----------|----------|-------|---------|
| Lịch thi đấu (schedule) | 1 lần/ngày (6h sáng) | API-Football, TheSportsDB | 🔴 P0 |
| Tỉ số live | Mỗi 30s khi có trận | API-Football | 🔴 P0 |
| Kết quả trận (final) | Sau trận kết thúc | API-Football | 🔴 P0 |
| Đội hình (lineups) | 30 phút trước trận | API-Football | 🟡 P1 |
| Sự kiện trận (events) | Mỗi 30s khi live | API-Football | 🟡 P1 |
| Bảng xếp hạng (standings) | 2 lần/ngày | API-Football, TheSportsDB | 🟡 P1 |
| Highlight video | 4 lần/ngày | Scorebat (free), YouTube | 🟢 P2 |
| Bài viết tổng kết | Crawl RSS/web | VnExpress, Bongda24h | 🟢 P2 |
| NBA scores | Mỗi 60s khi có trận | BallDontLie, TheSportsDB | 🟡 P1 |

### Nguồn data (Free tier)

| Nguồn | API | Free limit | Data |
|--------|-----|-----------|------|
| [API-Football](https://www.api-football.com/) | REST + JSON | 100 req/ngày (free) | Bóng đá: fixtures, live, standings, lineups, events |
| [TheSportsDB](https://www.thesportsdb.com/) | REST + JSON | Unlimited (Patreon $3) | Multi-sport: schedule, results, teams, leagues |
| [BallDontLie](https://www.balldontlie.io/) | REST + JSON | 30 req/phút | NBA: games, stats, teams |
| [Scorebat](https://www.scorebat.com/video-api/) | REST + JSON | Free embed | Highlight video (embed URL) |
| RSS Feeds | XML | Unlimited | VnExpress Thể thao, Bongda24h |

---

## 2. Kiến trúc Crawl

```
┌─────────────────────────────────────────────────────────┐
│                    SCHEDULER (Cron)                      │
│  schedule:run mỗi phút                                  │
└──────────┬──────────┬──────────┬──────────┬─────────────┘
           │          │          │          │
     06:00 daily  30s live   12h/24h    6h interval
           │          │          │          │
           ▼          ▼          ▼          ▼
┌──────────────┐ ┌─────────┐ ┌────────┐ ┌──────────────┐
│ SyncSchedule │ │SyncLive │ │SyncBXH │ │SyncHighlight │
│ (fixtures)   │ │(scores) │ │        │ │+ SyncArticle │
└──────┬───────┘ └────┬────┘ └───┬────┘ └──────┬───────┘
       │              │          │              │
       ▼              ▼          ▼              ▼
┌─────────────────────────────────────────────────────────┐
│              SportDataService (orchestrator)             │
│  - Gọi API nguồn                                        │
│  - Transform data → format chuẩn                        │
│  - Dedup check (slug/external_id)                       │
│  - Upsert vào DB                                        │
│  - Log kết quả                                          │
└──────────┬──────────────────────────────────────────────┘
           │
           ▼
┌─────────────────────────────────────────────────────────┐
│  DB: sport_matches, match_events, standings,            │
│      sport_highlights, sport_articles                   │
│  Cache: Redis (live scores 30s, standings 5m)           │
└─────────────────────────────────────────────────────────┘
```

---

## 3. Chống duplicate & Data rác

### 3.1 Duplicate Detection

| Data | Unique key | Strategy |
|------|-----------|----------|
| Match | `external_id` (từ API source) | `updateOrCreate` by external_id |
| Team | `id` (slug) | Đã có sẵn, map external_id → local id |
| Event | `match_id` + `type` + `minute` + `player_id` | Composite unique check |
| Highlight | `video_url` | Unique index, skip nếu trùng |
| Article | `source_url` | Unique index, skip nếu trùng |
| Standing | `league_id` + `team_id` | `updateOrCreate` |

### 3.2 Data rác filter

```
Incoming data
    │
    ▼
[1] Validate required fields (title, date, team_id...)
    │ ✗ → log + skip
    ▼
[2] Check league thuộc danh sách hỗ trợ (15 leagues)
    │ ✗ → skip (không lưu giải lạ)
    ▼
[3] Check team mapping tồn tại
    │ ✗ → log "unknown team" + skip hoặc auto-create
    ▼
[4] Check duplicate (external_id / composite key)
    │ ✗ → update nếu data mới hơn
    ▼
[5] Sanitize: strip HTML tags, trim whitespace, validate URLs
    │
    ▼
[6] Save to DB
```

### 3.3 Team ID Mapping

Mỗi nguồn API có ID riêng. Cần bảng mapping:

```sql
-- Thêm cột vào bảng teams
ALTER TABLE teams ADD COLUMN external_ids JSON NULLABLE;
-- Ví dụ: {"api_football": 33, "thesportsdb": 133604, "balldontlie": 14}
```

---

## 4. Chi tiết từng Crawler

### 4.1 SyncFixtures — Lịch thi đấu (P0)

**Chạy:** 06:00 hàng ngày
**Nguồn:** API-Football `/fixtures?date={today}&league={id}&season=2025`
**Logic:**
1. Lấy fixtures cho 15 leagues, ngày hôm nay + 7 ngày tới
2. Map team external_id → local team_id
3. `updateOrCreate` by external_id vào `sport_matches`
4. Status: `scheduled`

### 4.2 SyncLiveScores — Tỉ số trực tiếp (P0)

**Chạy:** Mỗi 30s khi có trận live (check flag cache)
**Nguồn:** API-Football `/fixtures?live=all`
**Logic:**
1. Chỉ chạy khi `Cache::get('sport:has_live_matches')` = true
2. Lấy tất cả trận live
3. Update: `status`, `minute`, `home_score`, `away_score`, `period`, `stats`
4. Khi trận kết thúc → set `status=finished`, clear live flag nếu không còn trận nào
5. Dispatch `SendGoalNotification` khi score thay đổi

### 4.3 SyncMatchEvents — Sự kiện trận (P1)

**Chạy:** Mỗi 30s cùng với live scores
**Nguồn:** API-Football `/fixtures/events?fixture={id}`
**Logic:**
1. Chỉ sync cho trận đang live
2. Dedup: `match_id` + `type` + `minute` + `player_name`
3. Insert new events only (không update)

### 4.4 SyncLineups — Đội hình (P1)

**Chạy:** 30 phút trước giờ kick-off
**Nguồn:** API-Football `/fixtures/lineups?fixture={id}`
**Logic:**
1. Query matches sắp diễn ra trong 30 phút
2. `updateOrCreate` by `match_id` + `team_side`

### 4.5 SyncStandings — Bảng xếp hạng (P1)

**Chạy:** 02:00 và 14:00 hàng ngày
**Nguồn:** API-Football `/standings?league={id}&season=2025`
**Logic:**
1. Lấy standings cho 15 leagues
2. `updateOrCreate` by `league_id` + `team_id`
3. Cache 5 phút

### 4.6 SyncHighlights — Video highlight (P2)

**Chạy:** Mỗi 6h (00:00, 06:00, 12:00, 18:00)
**Nguồn:** Scorebat API `https://www.scorebat.com/video-api/v3/feed`
**Logic:**
1. Lấy feed mới nhất
2. Match title → tìm `sport_matches` tương ứng (fuzzy match team name)
3. Dedup by `video_url`
4. Lưu: title, thumbnail, video_url (embed), duration

### 4.7 SyncArticles — Bài viết (P2)

**Chạy:** Mỗi 6h
**Nguồn:** RSS feeds
- `https://vnexpress.net/rss/the-thao.rss`
- `https://bongda24h.vn/RSS/`

**Logic:**
1. Parse RSS XML
2. Filter: chỉ lấy bài liên quan đến 15 leagues / 20 teams (keyword match)
3. Dedup by `source_url`
4. Lưu: title, summary (từ RSS description), image_url, type=`recap`
5. Content HTML: crawl full article nếu cần (optional, tốn bandwidth)

---

## 5. Database changes cần thiết

### 5.1 Thêm cột tracking cho crawl

```sql
-- sport_matches: thêm external tracking
ALTER TABLE sport_matches
  ADD COLUMN external_id VARCHAR(50) NULL AFTER id,
  ADD COLUMN source VARCHAR(30) DEFAULT 'manual',
  ADD COLUMN synced_at TIMESTAMP NULL,
  ADD UNIQUE INDEX idx_external_id (external_id);

-- teams: thêm external mapping
ALTER TABLE teams
  ADD COLUMN external_ids JSON NULL;

-- sport_highlights: thêm source tracking
ALTER TABLE sport_highlights
  ADD COLUMN source_url VARCHAR(500) NULL,
  ADD UNIQUE INDEX idx_source_url (source_url);

-- sport_articles: thêm source tracking
ALTER TABLE sport_articles
  ADD COLUMN source_url VARCHAR(500) NULL,
  ADD COLUMN source VARCHAR(100) NULL,
  ADD UNIQUE INDEX idx_source_url (source_url);

-- Bảng log crawl
CREATE TABLE sport_crawl_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  crawler VARCHAR(50) NOT NULL,
  source VARCHAR(100),
  status ENUM('success', 'failed', 'partial') NOT NULL,
  items_fetched INT DEFAULT 0,
  items_created INT DEFAULT 0,
  items_updated INT DEFAULT 0,
  items_skipped INT DEFAULT 0,
  error_message TEXT NULL,
  duration_ms INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 6. Scheduler config

```php
// bootstrap/app.php hoặc Console/Kernel.php

// P0: Lịch thi đấu — 06:00 hàng ngày
$schedule->command('sport:sync-fixtures')->dailyAt('06:00')->withoutOverlapping();

// P0: Live scores — mỗi 30s (chỉ khi có trận)
$schedule->command('sport:sync-live')->everyThirtySeconds()->withoutOverlapping()
    ->when(fn () => Cache::get('sport:has_live_matches', false));

// P1: Standings — 02:00 và 14:00
$schedule->command('sport:sync-standings')->twiceDaily(2, 14)->withoutOverlapping();

// P1: Lineups — mỗi 5 phút (check trận sắp diễn ra)
$schedule->command('sport:sync-lineups')->everyFiveMinutes()->withoutOverlapping();

// P2: Highlights — mỗi 6h
$schedule->command('sport:sync-highlights')->everySixHours()->withoutOverlapping();

// P2: Articles — mỗi 6h (lệch 1h so với highlights)
$schedule->command('sport:sync-articles')->everySixHours()->at('01:00')->withoutOverlapping();

// Maintenance: cleanup data rác > 90 ngày
$schedule->command('sport:cleanup')->weekly()->withoutOverlapping();
```

---

## 7. Thứ tự triển khai

| # | Task | Ước lượng | Phụ thuộc |
|---|------|-----------|-----------|
| 1 | Migration thêm cột tracking + crawl_logs | 0.5 ngày | — |
| 2 | `SportDataService` (base class: HTTP client, logging, dedup) | 0.5 ngày | #1 |
| 3 | Team ID mapping (external_ids cho 20 teams) | 0.5 ngày | #1 |
| 4 | `sport:sync-fixtures` command | 1 ngày | #2, #3 |
| 5 | `sport:sync-live` command | 1 ngày | #4 |
| 6 | `sport:sync-standings` command | 0.5 ngày | #3 |
| 7 | `sport:sync-lineups` + `sync-events` | 0.5 ngày | #5 |
| 8 | `sport:sync-highlights` (Scorebat) | 0.5 ngày | #2 |
| 9 | `sport:sync-articles` (RSS) | 0.5 ngày | #2 |
| 10 | `sport:cleanup` (xóa data cũ) | 0.5 ngày | — |
| 11 | Scheduler config + test end-to-end | 0.5 ngày | All |

**Tổng: ~6 ngày**

---

## 8. Monitoring & Alert

| Metric | Alert khi |
|--------|-----------|
| `sport_crawl_logs.status = 'failed'` | 3 lần liên tiếp |
| Live sync không chạy | Có trận live nhưng không có log trong 2 phút |
| Fixtures trống | 0 matches cho ngày mai (sau 06:00) |
| API quota | > 80% free tier limit |

Xem log:
```bash
docker exec -u root lg-php php artisan tinker --execute="
DB::table('sport_crawl_logs')->latest()->take(10)->get(['crawler','status','items_created','items_updated','items_skipped','error_message','created_at'])->each(fn(\$l) => print(\$l->crawler.' '.\$l->status.' +'.\$l->items_created.' ~'.\$l->items_updated.' -'.\$l->items_skipped.' '.\$l->created_at.PHP_EOL));
"
```

---

## 9. API Keys cần đăng ký

| Service | URL đăng ký | Env key |
|---------|-------------|---------|
| API-Football | https://dashboard.api-football.com/ | `API_FOOTBALL_KEY` |
| TheSportsDB | https://www.thesportsdb.com/patreon | `THESPORTSDB_KEY` |
| BallDontLie | https://app.balldontlie.io/ | `BALLDONTLIE_KEY` |
| Scorebat | https://www.scorebat.com/video-api/ | Không cần key (free embed) |
