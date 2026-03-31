# SportPulse API — Task List

> Branch: `feat/APISportPulse`
> Base URL: `https://lamgame.vn/api/v1/sport`
> Ngày tạo: 2026-03-31
> Cập nhật: 2026-03-31 17:55

---

## Tiến độ tổng quan

| Phase | Trạng thái | Ngày hoàn thành |
|-------|-----------|-----------------|
| Phase 1 — Foundation | ✅ DONE | 2026-03-31 |
| Phase 2–8 — API Endpoints | ✅ DONE (code sẵn trong Phase 1) | 2026-03-31 |
| Phase 9 — Crawl Data | 🔜 Tiếp tục ngày mai | — |
| Phase 10 — Push Notifications | ⬜ Chưa bắt đầu | — |

---

## PHASE 1: Foundation ✅ DONE (2026-03-31)

- [x] 3 migrations: 13 tables (sports, leagues, teams, league_team, sport_matches, match_events, match_lineups, standings, sport_highlights, sport_articles, user_sport_profiles, user_sport_reminders, user_sport_fcm_tokens)
- [x] 12 Eloquent models (`App\Models\Sport\*`)
- [x] 7 controllers với full implementation (không phải stub)
- [x] 26 routes (19 public + 8 Firebase Auth)
- [x] Seeder: 5 sports, 15 leagues, 20 teams
- [x] Migrate + test local: 13/13 tests passed
- [x] Deploy production: OK
- [x] Tài liệu: API Guide + Postman Collection

### Commits
| Commit | Mô tả |
|--------|-------|
| `d1ceaee` | docs: update domain, add TASKS.md |
| `b4afc1e` | feat: Phase 1 — models, controllers, routes, seeder |
| `762ae4d` | docs: API guide + test results |
| `19e0362` | docs: Postman collection 26 endpoints |
| `19312a0` | docs: crawl data plan |

---

## PHASE 9: Crawl & Tổng hợp Data 🔜 NGÀY MAI

> Chi tiết: `docs/API-SportPulse/04_CRAWL_PLAN.md`

### Task 9.1 — Migration thêm cột tracking + crawl_logs
- [ ] Thêm `external_id`, `source`, `synced_at` vào `sport_matches`
- [ ] Thêm `external_ids` JSON vào `teams`
- [ ] Thêm `source_url` unique vào `sport_highlights`, `sport_articles`
- [ ] Tạo bảng `sport_crawl_logs`

### Task 9.2 — SportDataService (base class)
- [ ] HTTP client (Guzzle) với retry, timeout
- [ ] Logging vào `sport_crawl_logs`
- [ ] Dedup helper methods

### Task 9.3 — Team ID mapping
- [ ] Map 20 teams → external_ids (API-Football, TheSportsDB, BallDontLie)
- [ ] Migration seed external_ids

### Task 9.4 — `sport:sync-fixtures` (P0)
- [ ] Crawl lịch thi đấu từ API-Football
- [ ] Chạy 06:00 hàng ngày
- [ ] updateOrCreate by external_id

### Task 9.5 — `sport:sync-live` (P0)
- [ ] Crawl tỉ số trực tiếp mỗi 30s
- [ ] Chỉ chạy khi có trận live (cache flag)
- [ ] Dispatch goal notification khi score thay đổi

### Task 9.6 — `sport:sync-standings` (P1)
- [ ] Crawl BXH 2 lần/ngày (02:00, 14:00)
- [ ] updateOrCreate by league_id + team_id

### Task 9.7 — `sport:sync-lineups` + `sync-events` (P1)
- [ ] Lineups: 30 phút trước kick-off
- [ ] Events: mỗi 30s cùng live scores

### Task 9.8 — `sport:sync-highlights` (P2)
- [ ] Crawl Scorebat API mỗi 6h
- [ ] Dedup by video_url
- [ ] Fuzzy match team name → match_id

### Task 9.9 — `sport:sync-articles` (P2)
- [ ] Parse RSS: VnExpress Thể thao, Bongda24h
- [ ] Filter keyword match 15 leagues / 20 teams
- [ ] Dedup by source_url

### Task 9.10 — `sport:cleanup`
- [ ] Xóa data cũ > 90 ngày (matches finished, old articles)
- [ ] Chạy weekly

### Task 9.11 — Scheduler config + test
- [ ] Đăng ký API keys (API-Football, TheSportsDB, BallDontLie)
- [ ] Config scheduler trong bootstrap/app.php
- [ ] Test end-to-end trên local

**Ước lượng: ~6 ngày**

---

## PHASE 10: Push Notifications ⬜

### Task 10.1 — Notification jobs
- [ ] `SendMatchStartNotification`
- [ ] `SendGoalNotification`
- [ ] `SendMatchEndNotification`
- [ ] `SendMatchReminderNotification` (check mỗi phút)
- [ ] `SendHighlightNotification`

**Ước lượng: ~1 ngày**

---

## Việc cần chuẩn bị trước ngày mai

- [ ] Đăng ký API key: https://dashboard.api-football.com/ (free 100 req/ngày)
- [ ] Đăng ký BallDontLie: https://app.balldontlie.io/ (free)
- [ ] Thêm vào `.env`: `API_FOOTBALL_KEY`, `BALLDONTLIE_KEY`

---

## Ghi chú kỹ thuật

- Tất cả endpoints public dùng `throttle:60,1`
- User endpoints dùng `firebase.auth` middleware
- Cache: Redis (đã có `lg-redis` container)
- Pagination: `limit=20, max=50`
- Response format: `{ "data": ..., "pagination": { page, limit, total } }`
- CDN assets: `https://lamgame.vn/storage/sport/`
- Postman: `docs/API-SportPulse/SportPulse_API.postman_collection.json`
