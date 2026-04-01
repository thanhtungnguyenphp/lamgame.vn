# SportPulse API — Tài liệu tích hợp

**Base URL:** `https://lamgame.vn/api/v1/sport`
**Version:** 1.0
**Cập nhật:** 2026-03-31

---

## Xác thực

| Nhóm | Auth | Mô tả |
|------|------|-------|
| Public (19 endpoints) | Không cần | Sports, leagues, matches, content, teams, search |
| User (8 endpoints) | Firebase Auth | Bearer token trong header `Authorization` |

```
Authorization: Bearer <firebase_id_token>
```

**Lỗi xác thực (401):**
```json
{"status": "error", "error": {"code": "UNAUTHORIZED", "message": "Missing authorization token."}}
```

---

## Rate Limiting

| Nhóm | Giới hạn |
|------|----------|
| Public endpoints | 60 req/phút |
| User endpoints | 30 req/phút |

---

## 1. Sports & Leagues

### GET `/sports`
Danh sách môn thể thao. Cache 24h.

```json
// 200
{"data": [{"id": "football", "name": "Bóng đá", "icon": "⚽", "order": 1}, ...]}
```

### GET `/leagues`
| Param | Type | Mô tả |
|-------|------|-------|
| sport | string | Filter: `football`, `basketball`, `tennis`, `mma`, `esports` |
| country | string | Filter: `England`, `Spain`, `Vietnam`... |
| season | string | `2025-2026` |

```json
// 200
{"data": [{"id": "premier-league", "name": "Premier League", "sport_id": "football", "country": "England", "logo_url": "...", "season": "2025-2026", "is_active": true, "order": 1}]}
```

### GET `/leagues/{id}/standings`
Bảng xếp hạng.

```json
// 200
{
  "league": {"id": "premier-league", "name": "Premier League", "season": "2025-2026"},
  "data": [{"rank": 1, "team": {"id": "arsenal", "name": "Arsenal", "short_name": "ARS", "logo_url": "..."}, "played": 30, "won": 22, "drawn": 5, "lost": 3, "goals_for": 68, "goals_against": 25, "goal_difference": 43, "points": 71, "form": ["W","W","D","W","L"]}]
}
```

### GET `/leagues/{id}/top-scorers`
Vua phá lưới. (Placeholder — sẽ implement khi có data source)

---

## 2. Matches

### GET `/matches/live`
Trận đang diễn ra.

| Param | Type | Mô tả |
|-------|------|-------|
| sport | string | Filter theo môn (optional) |

**Giá trị `status`:** `scheduled`, `live`, `halftime`, `finished`, `postponed`, `cancelled`

### GET `/matches/schedule`
Lịch thi đấu theo ngày, group by league.

| Param | Type | Mô tả |
|-------|------|-------|
| date | string | **Bắt buộc.** `YYYY-MM-DD` |
| sport | string | Filter (optional) |
| league_id | string | Filter (optional) |

```json
// 200
{
  "date": "2026-03-31",
  "data": [{"league": {"id": "premier-league", "name": "Premier League"}, "matches": [{"id": "match-123", "home_team": {...}, "away_team": {...}, "start_time": "2026-03-31T20:00:00Z", "status": "scheduled"}]}]
}
```

### GET `/matches/results`
| Param | Type | Default | Mô tả |
|-------|------|---------|-------|
| date | string | today | `YYYY-MM-DD` |
| sport | string | — | Filter |
| league_id | string | — | Filter |
| page | int | 1 | Trang |
| limit | int | 20 | Max 50 |

### GET `/matches/{id}`
Chi tiết trận, bao gồm `stats` (possession, shots, corners, fouls, cards).

### GET `/matches/{id}/events`
Sự kiện: `goal`, `own_goal`, `penalty_goal`, `penalty_miss`, `yellow_card`, `red_card`, `second_yellow`, `substitution`, `var_decision`

### GET `/matches/{id}/lineups`
Đội hình: formation, starting XI, substitutes.

### GET `/matches/{id}/h2h`
Lịch sử đối đầu: total, wins, draws, 10 trận gần nhất.

---

## 3. Content

### GET `/highlights`
| Param | Type | Mô tả |
|-------|------|-------|
| sport | string | Filter |
| league_id | string | Filter |
| match_id | string | Highlight của 1 trận |
| page | int | Trang |
| limit | int | Max 50 |

### GET `/articles`
| Param | Type | Mô tả |
|-------|------|-------|
| type | string | `recap`, `preview`, `opinion`, `roundup` |
| sport | string | Filter |
| page | int | Trang |
| limit | int | Max 50 |

### GET `/articles/{id}`
Chi tiết bài viết, bao gồm `content` (HTML) và `related_matches`.

### GET `/discover`
Feed xen kẽ highlight + article, sort by `created_at` DESC.

| Param | Type | Mô tả |
|-------|------|-------|
| sport | string | Filter |
| page | int | Trang |
| limit | int | Max 50 |

```json
// 200
{"data": [{"type": "highlight", "item": {...}}, {"type": "article", "item": {...}}]}
```

---

## 4. Teams

### GET `/teams/{id}`
Thông tin đội + danh sách leagues.

### GET `/teams/{id}/matches`
| Param | Type | Default | Mô tả |
|-------|------|---------|-------|
| status | string | all | `scheduled`, `finished`, `all` |
| page | int | 1 | Trang |
| limit | int | 20 | Max 50 |

---

## 5. Search

### GET `/search`
| Param | Type | Mô tả |
|-------|------|-------|
| q | string | **Bắt buộc.** Min 2 ký tự |
| type | string | `team`, `league`, `match`, `all` (default) |

```json
// 200
{"data": {"teams": [...], "leagues": [...], "matches": [...]}}
```

---

## 6. User (Firebase Auth required)

### GET `/user/profile`
Lấy hoặc tạo profile. Auto-create nếu chưa có.

### PUT `/user/favorites`
```json
{"favorite_teams": ["man-utd", "lakers"], "favorite_sports": ["football", "basketball"]}
```

### PUT `/user/notification-settings`
```json
{"live_score": true, "match_reminder": true, "highlights": true, "favorite_teams_only": false}
```

### POST `/user/reminders`
```json
{"match_id": "match-12345", "remind_before_minutes": 15}
```

### DELETE `/user/reminders/{match_id}`

### GET `/user/reminders`
Danh sách trận đã đặt nhắc, kèm thông tin match.

### POST `/user/fcm-token`
```json
{"token": "fcm-device-token", "platform": "android"}
```

### DELETE `/user/account`
Xóa tài khoản và toàn bộ data liên quan.

---

## Tổng hợp Endpoints

| # | Method | Endpoint | Auth | Rate |
|---|--------|----------|------|------|
| 1 | GET | `/sports` | — | 60/m |
| 2 | GET | `/leagues` | — | 60/m |
| 3 | GET | `/leagues/{id}/standings` | — | 60/m |
| 4 | GET | `/leagues/{id}/top-scorers` | — | 60/m |
| 5 | GET | `/matches/live` | — | 60/m |
| 6 | GET | `/matches/schedule` | — | 60/m |
| 7 | GET | `/matches/results` | — | 60/m |
| 8 | GET | `/matches/{id}` | — | 60/m |
| 9 | GET | `/matches/{id}/events` | — | 60/m |
| 10 | GET | `/matches/{id}/lineups` | — | 60/m |
| 11 | GET | `/matches/{id}/h2h` | — | 60/m |
| 12 | GET | `/highlights` | — | 60/m |
| 13 | GET | `/articles` | — | 60/m |
| 14 | GET | `/articles/{id}` | — | 60/m |
| 15 | GET | `/discover` | — | 60/m |
| 16 | GET | `/teams/{id}` | — | 60/m |
| 17 | GET | `/teams/{id}/matches` | — | 60/m |
| 18 | GET | `/search` | — | 60/m |
| 19 | GET | `/user/profile` | Firebase | 30/m |
| 20 | PUT | `/user/favorites` | Firebase | 30/m |
| 21 | PUT | `/user/notification-settings` | Firebase | 30/m |
| 22 | POST | `/user/reminders` | Firebase | 30/m |
| 23 | DELETE | `/user/reminders/{matchId}` | Firebase | 30/m |
| 24 | GET | `/user/reminders` | Firebase | 30/m |
| 25 | POST | `/user/fcm-token` | Firebase | 30/m |
| 26 | DELETE | `/user/account` | Firebase | 30/m |

---

## Seed Data (có sẵn)

- 5 môn: Football, Basketball, Tennis, MMA, Esports
- 15 giải: EPL, La Liga, Serie A, Bundesliga, Ligue 1, UCL, V-League, NBA, EuroLeague, ATP, WTA, UFC, ONE, LCK, VCS
- 20 đội: Arsenal, Man City, Man Utd, Liverpool, Chelsea, Tottenham, Barcelona, Real Madrid, Atlético, Inter, AC Milan, Juventus, Bayern, Dortmund, PSG, Lakers, Warriors, Celtics, Bucks, Nuggets

## Test Results (Local)

**Postman Collection:** `docs/API-SportPulse/SportPulse_API.postman_collection.json`
Import vào Postman, set variable `firebaseToken` nếu cần test user endpoints.

| # | Test | Status | HTTP |
|---|------|--------|------|
| 1 | GET /sports | ✅ | 200 |
| 2 | GET /leagues?sport=football | ✅ 7 leagues | 200 |
| 3 | GET /leagues/premier-league/standings | ✅ | 200 |
| 4 | GET /matches/live | ✅ empty | 200 |
| 5 | GET /matches/schedule?date=2026-03-31 | ✅ | 200 |
| 6 | GET /teams/arsenal | ✅ | 200 |
| 7 | GET /search?q=arsenal | ✅ | 200 |
| 8 | GET /teams/nonexistent | ✅ | 404 |
| 9 | GET /highlights | ✅ | 200 |
| 10 | GET /discover | ✅ | 200 |
| 11 | GET /user/profile (no auth) | ✅ blocked | 401 |
| 12 | GET /matches/schedule (no date) | ✅ validation | 422 |
| 13 | GET /search?q=a | ✅ validation | 422 |
