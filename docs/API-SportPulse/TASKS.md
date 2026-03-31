# SportPulse API — Task List

> Branch: `feat/APISportPulse`
> Base URL: `https://lamgame.vn/api/v1/sport`
> Ngày tạo: 2026-03-31

---

## Phân tích tổng quan

Tài liệu yêu cầu 26 endpoints chia thành 7 nhóm. Backend sẽ tích hợp vào hệ thống Laravel hiện có (lamgame.vn), sử dụng Firebase Auth cho user endpoints, throttle middleware cho rate limiting.

### Kiến trúc

```
Flutter App → lamgame.vn/api/v1/sport/* → Laravel Controllers → MySQL + Cache
                                              │
                                              ├── FirebaseAuth middleware (user endpoints)
                                              ├── Cache layer (Redis/file) cho data thể thao
                                              └── FCM push notifications
```

### Database tables mới cần tạo

| Table | Mô tả |
|-------|-------|
| `sports` | Danh sách môn thể thao |
| `leagues` | Giải đấu |
| `teams` | Đội |
| `league_team` | Pivot: đội thuộc giải nào |
| `matches` | Trận đấu |
| `match_events` | Sự kiện trận (bàn thắng, thẻ, thay người) |
| `match_lineups` | Đội hình |
| `standings` | Bảng xếp hạng |
| `highlights` | Video highlight |
| `sport_articles` | Bài viết tổng kết/bình luận |
| `user_sport_profiles` | Profile thể thao user (favorites, notification settings) |
| `user_reminders` | Nhắc nhở trận đấu |
| `user_fcm_tokens` | FCM tokens (có thể dùng chung bảng hiện có) |

---

## PHASE 1: Foundation (Database + Models + Routes)

### Task 1.1 — Migration & Models
Tạo migrations và Eloquent models cho tất cả tables.

- [ ] Migration: `sports`, `leagues`, `teams`, `league_team`
- [ ] Migration: `matches`, `match_events`, `match_lineups`
- [ ] Migration: `standings`
- [ ] Migration: `highlights`, `sport_articles`
- [ ] Migration: `user_sport_profiles`, `user_reminders`
- [ ] Models với relationships, casts, scopes

### Task 1.2 — Routes & Controller stubs
- [ ] Tạo `routes/api/sport.php`
- [ ] Include trong `routes/api.php`
- [ ] Tạo controller stubs cho 7 nhóm

### Task 1.3 — Seeder data cơ bản
- [ ] Seed sports (5 môn)
- [ ] Seed leagues chính (EPL, La Liga, Serie A, Bundesliga, Ligue 1, UCL, V-League, NBA, Grand Slams, UFC, VCS)
- [ ] Seed teams phổ biến (top 20 CLB bóng đá + NBA teams)

---

## PHASE 2: Sports & Leagues (4 endpoints)

### Task 2.1 — GET `/sports`
- [ ] `SportController@index`
- [ ] Response: id, name, icon, order
- [ ] Cache 24h

### Task 2.2 — GET `/leagues`
- [ ] `LeagueController@index`
- [ ] Query params: sport, country, season
- [ ] Cache 1h

### Task 2.3 — GET `/leagues/{id}/standings`
- [ ] `LeagueController@standings`
- [ ] Response: rank, team, played, won, drawn, lost, GF, GA, GD, points, form
- [ ] Cache 5 phút

### Task 2.4 — GET `/leagues/{id}/top-scorers`
- [ ] `LeagueController@topScorers`
- [ ] Cache 1h

---

## PHASE 3: Matches — Core (7 endpoints)

### Task 3.1 — GET `/matches/live`
- [ ] `MatchController@live`
- [ ] Filter: sport
- [ ] Cache 30s (trận live cần refresh nhanh)

### Task 3.2 — GET `/matches/schedule`
- [ ] `MatchController@schedule`
- [ ] Query: date (required), sport, league_id
- [ ] Group by league
- [ ] Cache 5 phút

### Task 3.3 — GET `/matches/results`
- [ ] `MatchController@results`
- [ ] Query: date, sport, league_id, page, limit
- [ ] Pagination
- [ ] Cache 5 phút

### Task 3.4 — GET `/matches/{id}`
- [ ] `MatchController@show`
- [ ] Include: stats (possession, shots, corners, fouls, cards)
- [ ] Cache 1 phút (live) / 1h (finished)

### Task 3.5 — GET `/matches/{id}/events`
- [ ] `MatchController@events`
- [ ] Types: goal, own_goal, penalty_goal, penalty_miss, yellow_card, red_card, second_yellow, substitution, var_decision
- [ ] Cache 30s

### Task 3.6 — GET `/matches/{id}/lineups`
- [ ] `MatchController@lineups`
- [ ] Response: formation, starting XI, substitutes
- [ ] Cache 5 phút

### Task 3.7 — GET `/matches/{id}/h2h`
- [ ] `MatchController@h2h`
- [ ] Response: total, wins, draws, recent matches
- [ ] Cache 1h

---

## PHASE 4: Content (4 endpoints)

### Task 4.1 — GET `/highlights`
- [ ] `HighlightController@index`
- [ ] Query: sport, league_id, match_id, page, limit
- [ ] Pagination

### Task 4.2 — GET `/articles`
- [ ] `ArticleController@index`
- [ ] Query: type (recap/preview/opinion/roundup), sport, page, limit

### Task 4.3 — GET `/articles/{id}`
- [ ] `ArticleController@show`
- [ ] Include: related_matches

### Task 4.4 — GET `/discover`
- [ ] `DiscoverController@index`
- [ ] Feed xen kẽ highlight + article, sort by created_at DESC
- [ ] Query: sport, page, limit

---

## PHASE 5: Teams (2 endpoints)

### Task 5.1 — GET `/teams/{id}`
- [ ] `TeamController@show`
- [ ] Include: leagues
- [ ] Cache 1h

### Task 5.2 — GET `/teams/{id}/matches`
- [ ] `TeamController@matches`
- [ ] Query: status (scheduled/finished/all), page, limit

---

## PHASE 6: User & Favorites (8 endpoints, Firebase Auth)

### Task 6.1 — GET `/user/profile`
- [ ] `SportUserController@profile`
- [ ] Middleware: `firebase.auth`
- [ ] Response: favorites, notification_settings, is_premium

### Task 6.2 — PUT `/user/favorites`
- [ ] `SportUserController@updateFavorites`
- [ ] Request: favorite_teams[], favorite_sports[]

### Task 6.3 — PUT `/user/notification-settings`
- [ ] `SportUserController@updateNotificationSettings`
- [ ] Request: live_score, match_reminder, highlights, favorite_teams_only

### Task 6.4 — POST `/user/reminders`
- [ ] `ReminderController@store`
- [ ] Request: match_id, remind_before_minutes

### Task 6.5 — DELETE `/user/reminders/{match_id}`
- [ ] `ReminderController@destroy`

### Task 6.6 — GET `/user/reminders`
- [ ] `ReminderController@index`

### Task 6.7 — DELETE `/user/account`
- [ ] `SportUserController@deleteAccount`

### Task 6.8 — POST `/user/fcm-token`
- [ ] `SportUserController@registerFcmToken`
- [ ] Request: token, platform

---

## PHASE 7: Search (1 endpoint)

### Task 7.1 — GET `/search`
- [ ] `SearchController@index`
- [ ] Query: q (min 2 chars), type (team/league/match/all)
- [ ] LIKE search trên teams.name, leagues.name, matches (home/away team name)

---

## PHASE 8: Push Notifications (server-side)

### Task 8.1 — Notification jobs
- [ ] `SendMatchStartNotification` job
- [ ] `SendGoalNotification` job
- [ ] `SendMatchEndNotification` job
- [ ] `SendMatchReminderNotification` job (scheduled, check mỗi phút)
- [ ] `SendHighlightNotification` job

---

## Thứ tự triển khai đề xuất

| Thứ tự | Phase | Ước lượng | Lý do ưu tiên |
|--------|-------|-----------|----------------|
| 1 | Phase 1 — Foundation | 1 ngày | Nền tảng cho tất cả |
| 2 | Phase 2 — Sports & Leagues | 0.5 ngày | Data tĩnh, đơn giản |
| 3 | Phase 3 — Matches | 1.5 ngày | Core feature, nhiều endpoint nhất |
| 4 | Phase 5 — Teams | 0.5 ngày | Đơn giản, phụ thuộc Phase 3 |
| 5 | Phase 4 — Content | 1 ngày | Highlight + articles |
| 6 | Phase 6 — User | 1 ngày | Cần Firebase Auth |
| 7 | Phase 7 — Search | 0.5 ngày | Đơn giản |
| 8 | Phase 8 — Push | 1 ngày | Cần test với device thật |

**Tổng ước lượng: ~7 ngày**

---

## Ghi chú kỹ thuật

- Tất cả endpoints public (không cần auth) dùng `throttle:60,1`
- User endpoints dùng `firebase.auth` middleware (đã có sẵn)
- Cache strategy: Redis nếu có, fallback file cache
- Pagination mặc định: `limit=20, max=50`
- Response format thống nhất: `{ "data": ..., "pagination": { page, limit, total } }`
- CDN assets: `https://lamgame.vn/storage/sport/` (teams logos, league logos, highlights, articles)
