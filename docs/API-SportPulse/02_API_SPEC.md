# SportPulse – API Specification

**Base URL:** `https://lamgame.vn/api/v1/sport`
**Auth:** Bearer token (Firebase Auth ID token)
**Format:** JSON
**Pagination:** `?page=1&limit=20` (mặc định limit=20, max=50)

---

## 1. SPORTS & LEAGUES

### 1.1 GET /sports
Danh sách môn thể thao hỗ trợ.

**Response:**
```json
{
  "data": [
    {
      "id": "football",
      "name": "Bóng đá",
      "icon": "⚽",
      "order": 1
    },
    {
      "id": "basketball",
      "name": "Bóng rổ",
      "icon": "🏀",
      "order": 2
    }
  ]
}
```

---

### 1.2 GET /leagues
Danh sách giải đấu.

**Query params:**
| Param | Type | Mô tả |
|-------|------|--------|
| sport | string | Filter theo môn: `football`, `basketball`, `tennis`, `mma`, `esports` |
| country | string | Filter theo quốc gia: `england`, `spain`, `vietnam`... |
| season | string | Mùa giải: `2025-2026` |

**Response:**
```json
{
  "data": [
    {
      "id": "premier-league",
      "name": "Premier League",
      "sport": "football",
      "country": "England",
      "logo_url": "https://lamgame.vn/storage/sport/leagues/epl.png",
      "season": "2025-2026",
      "is_active": true
    }
  ]
}
```

---

### 1.3 GET /leagues/{league_id}/standings
Bảng xếp hạng của giải.

**Response:**
```json
{
  "league": { "id": "premier-league", "name": "Premier League", "season": "2025-2026" },
  "data": [
    {
      "rank": 1,
      "team": {
        "id": "arsenal",
        "name": "Arsenal",
        "short_name": "ARS",
        "logo_url": "https://lamgame.vn/storage/sport/teams/arsenal.png"
      },
      "played": 30,
      "won": 22,
      "drawn": 5,
      "lost": 3,
      "goals_for": 68,
      "goals_against": 25,
      "goal_difference": 43,
      "points": 71,
      "form": ["W", "W", "D", "W", "L"]
    }
  ]
}
```

---

### 1.4 GET /leagues/{league_id}/top-scorers
Vua phá lưới.

**Response:**
```json
{
  "data": [
    {
      "rank": 1,
      "player": { "id": "haaland", "name": "Erling Haaland", "photo_url": "..." },
      "team": { "id": "man-city", "name": "Manchester City", "logo_url": "..." },
      "goals": 25,
      "assists": 5,
      "matches_played": 28
    }
  ]
}
```

---

## 2. MATCHES

### 2.1 GET /matches/live
Trận đang diễn ra (live).

**Query params:**
| Param | Type | Mô tả |
|-------|------|--------|
| sport | string | Filter theo môn (optional) |

**Response:**
```json
{
  "data": [
    {
      "id": "match-12345",
      "home_team": {
        "id": "man-utd",
        "name": "Manchester United",
        "short_name": "MU",
        "logo_url": "https://lamgame.vn/storage/sport/teams/mu.png"
      },
      "away_team": {
        "id": "arsenal",
        "name": "Arsenal",
        "short_name": "ARS",
        "logo_url": "https://lamgame.vn/storage/sport/teams/arsenal.png"
      },
      "league": {
        "id": "premier-league",
        "name": "Premier League",
        "logo_url": "..."
      },
      "sport": "football",
      "status": "live",
      "minute": 67,
      "period": "2nd_half",
      "home_score": 2,
      "away_score": 1,
      "start_time": "2026-03-31T20:00:00Z",
      "venue": "Old Trafford"
    }
  ]
}
```

**Giá trị `status`:**
- `scheduled` – Chưa bắt đầu
- `live` – Đang diễn ra
- `halftime` – Giữa hiệp
- `finished` – Đã kết thúc
- `postponed` – Hoãn
- `cancelled` – Hủy

---

### 2.2 GET /matches/schedule
Lịch thi đấu theo ngày.

**Query params:**
| Param | Type | Mô tả |
|-------|------|--------|
| date | string | `YYYY-MM-DD` (bắt buộc) |
| sport | string | Filter theo môn (optional) |
| league_id | string | Filter theo giải (optional) |

**Response:**
```json
{
  "date": "2026-03-31",
  "data": [
    {
      "league": { "id": "premier-league", "name": "Premier League", "logo_url": "..." },
      "matches": [
        {
          "id": "match-12345",
          "home_team": { "id": "man-utd", "name": "Manchester United", "short_name": "MU", "logo_url": "..." },
          "away_team": { "id": "arsenal", "name": "Arsenal", "short_name": "ARS", "logo_url": "..." },
          "start_time": "2026-03-31T20:00:00Z",
          "status": "scheduled",
          "home_score": null,
          "away_score": null,
          "sport": "football"
        }
      ]
    }
  ]
}
```

---

### 2.3 GET /matches/results
Kết quả trận đã kết thúc.

**Query params:**
| Param | Type | Mô tả |
|-------|------|--------|
| date | string | `YYYY-MM-DD` (optional, mặc định hôm nay) |
| sport | string | Filter theo môn (optional) |
| league_id | string | Filter theo giải (optional) |
| page | int | Trang |
| limit | int | Số lượng |

**Response:** Cùng format với `/matches/schedule`, nhưng `status = "finished"` và có score.

---

### 2.4 GET /matches/{match_id}
Chi tiết 1 trận đấu.

**Response:**
```json
{
  "data": {
    "id": "match-12345",
    "home_team": { "id": "man-utd", "name": "Manchester United", "short_name": "MU", "logo_url": "..." },
    "away_team": { "id": "arsenal", "name": "Arsenal", "short_name": "ARS", "logo_url": "..." },
    "league": { "id": "premier-league", "name": "Premier League" },
    "sport": "football",
    "status": "live",
    "minute": 67,
    "period": "2nd_half",
    "home_score": 2,
    "away_score": 1,
    "start_time": "2026-03-31T20:00:00Z",
    "venue": "Old Trafford",
    "referee": "Michael Oliver",
    "stats": {
      "home": { "possession": 55, "shots": 12, "shots_on_target": 6, "corners": 5, "fouls": 10, "yellow_cards": 1, "red_cards": 0 },
      "away": { "possession": 45, "shots": 8, "shots_on_target": 4, "corners": 3, "fouls": 12, "yellow_cards": 2, "red_cards": 0 }
    }
  }
}
```

---

### 2.5 GET /matches/{match_id}/events
Sự kiện trong trận (bàn thắng, thẻ, thay người).

**Response:**
```json
{
  "data": [
    {
      "id": "evt-001",
      "type": "goal",
      "minute": 23,
      "extra_minute": null,
      "team": "home",
      "player": { "id": "rashford", "name": "Marcus Rashford" },
      "assist": { "id": "bruno", "name": "Bruno Fernandes" },
      "detail": "Normal Goal"
    },
    {
      "id": "evt-002",
      "type": "yellow_card",
      "minute": 35,
      "team": "away",
      "player": { "id": "saliba", "name": "William Saliba" },
      "detail": "Foul"
    },
    {
      "id": "evt-003",
      "type": "substitution",
      "minute": 60,
      "team": "home",
      "player_in": { "id": "garnacho", "name": "Alejandro Garnacho" },
      "player_out": { "id": "antony", "name": "Antony" }
    }
  ]
}
```

**Giá trị `type`:** `goal`, `own_goal`, `penalty_goal`, `penalty_miss`, `yellow_card`, `red_card`, `second_yellow`, `substitution`, `var_decision`

---

### 2.6 GET /matches/{match_id}/lineups
Đội hình ra sân.

**Response:**
```json
{
  "data": {
    "home": {
      "formation": "4-2-3-1",
      "starting": [
        { "id": "onana", "name": "André Onana", "number": 24, "position": "GK" },
        { "id": "dalot", "name": "Diogo Dalot", "number": 20, "position": "RB" }
      ],
      "substitutes": [
        { "id": "garnacho", "name": "Alejandro Garnacho", "number": 17, "position": "FW" }
      ]
    },
    "away": {
      "formation": "4-3-3",
      "starting": [],
      "substitutes": []
    }
  }
}
```

---

### 2.7 GET /matches/{match_id}/h2h
Lịch sử đối đầu.

**Response:**
```json
{
  "data": {
    "total_matches": 20,
    "home_wins": 8,
    "away_wins": 7,
    "draws": 5,
    "recent_matches": [
      {
        "id": "match-old-001",
        "date": "2025-12-01",
        "home_team": { "id": "man-utd", "short_name": "MU" },
        "away_team": { "id": "arsenal", "short_name": "ARS" },
        "home_score": 1,
        "away_score": 1,
        "league": "Premier League"
      }
    ]
  }
}
```

---

## 3. HIGHLIGHTS & CONTENT

### 3.1 GET /highlights
Danh sách video highlight.

**Query params:**
| Param | Type | Mô tả |
|-------|------|--------|
| sport | string | Filter theo môn (optional) |
| league_id | string | Filter theo giải (optional) |
| match_id | string | Highlight của 1 trận cụ thể (optional) |
| page | int | Trang |
| limit | int | Số lượng |

**Response:**
```json
{
  "data": [
    {
      "id": "hl-001",
      "title": "Manchester United 3-1 Fulham | All Goals & Highlights",
      "thumbnail_url": "https://lamgame.vn/storage/sport/highlights/hl-001.jpg",
      "video_url": "https://lamgame.vn/storage/sport/highlights/hl-001.mp4",
      "duration": 185,
      "view_count": 12500,
      "sport": "football",
      "match_id": "match-12340",
      "league": { "id": "premier-league", "name": "Premier League" },
      "created_at": "2026-03-30T22:00:00Z"
    }
  ],
  "pagination": { "page": 1, "limit": 20, "total": 150 }
}
```

---

### 3.2 GET /articles
Bài viết tổng kết, bình luận.

**Query params:**
| Param | Type | Mô tả |
|-------|------|--------|
| type | string | `recap`, `preview`, `opinion`, `roundup` (optional) |
| sport | string | Filter theo môn (optional) |
| page | int | Trang |
| limit | int | Số lượng |

**Response:**
```json
{
  "data": [
    {
      "id": "art-001",
      "title": "Tổng kết vòng 30 Premier League: Arsenal vững ngôi đầu",
      "summary": "Arsenal tiếp tục chuỗi bất bại với chiến thắng 2-0 trước...",
      "image_url": "https://lamgame.vn/storage/sport/articles/art-001.jpg",
      "type": "roundup",
      "sport": "football",
      "read_time_minutes": 5,
      "created_at": "2026-03-31T08:00:00Z"
    }
  ],
  "pagination": { "page": 1, "limit": 20, "total": 80 }
}
```

---

### 3.3 GET /articles/{article_id}
Chi tiết bài viết.

**Response:**
```json
{
  "data": {
    "id": "art-001",
    "title": "Tổng kết vòng 30 Premier League",
    "summary": "...",
    "content": "<p>Nội dung HTML đầy đủ...</p>",
    "image_url": "...",
    "type": "roundup",
    "sport": "football",
    "related_matches": ["match-12345", "match-12346"],
    "read_time_minutes": 5,
    "created_at": "2026-03-31T08:00:00Z"
  }
}
```

---

### 3.4 GET /discover
Feed tổng hợp (highlight + article xen kẽ), dùng cho tab Khám phá.

**Query params:**
| Param | Type | Mô tả |
|-------|------|--------|
| sport | string | Filter (optional) |
| page | int | Trang |
| limit | int | Số lượng |

**Response:**
```json
{
  "data": [
    {
      "type": "highlight",
      "item": { "id": "hl-001", "title": "...", "thumbnail_url": "...", "video_url": "...", "duration": 185, "view_count": 12500 }
    },
    {
      "type": "article",
      "item": { "id": "art-001", "title": "...", "summary": "...", "image_url": "...", "read_time_minutes": 5 }
    }
  ],
  "pagination": { "page": 1, "limit": 20, "total": 200 }
}
```

---

## 4. TEAMS

### 4.1 GET /teams/{team_id}
Thông tin đội.

**Response:**
```json
{
  "data": {
    "id": "man-utd",
    "name": "Manchester United",
    "short_name": "MU",
    "logo_url": "...",
    "sport": "football",
    "country": "England",
    "venue": "Old Trafford",
    "founded": 1878,
    "leagues": [
      { "id": "premier-league", "name": "Premier League" },
      { "id": "champions-league", "name": "Champions League" }
    ]
  }
}
```

### 4.2 GET /teams/{team_id}/matches
Lịch thi đấu & kết quả của 1 đội.

**Query params:**
| Param | Type | Mô tả |
|-------|------|--------|
| status | string | `scheduled`, `finished`, `all` (mặc định `all`) |
| page | int | Trang |
| limit | int | Số lượng |

**Response:** Cùng format match list.

---

## 5. USER & FAVORITES

### 5.1 GET /user/profile
Lấy profile user (cần auth).

**Response:**
```json
{
  "data": {
    "uid": "firebase-uid",
    "display_name": "Tung Nguyen",
    "email": "...",
    "photo_url": "...",
    "favorite_teams": ["man-utd", "lakers"],
    "favorite_sports": ["football", "basketball"],
    "notification_settings": {
      "live_score": true,
      "match_reminder": true,
      "highlights": true,
      "favorite_teams_only": false
    },
    "is_premium": false,
    "created_at": "2026-03-31T10:00:00Z"
  }
}
```

---

### 5.2 PUT /user/favorites
Cập nhật đội/môn yêu thích.

**Request body:**
```json
{
  "favorite_teams": ["man-utd", "lakers", "arsenal"],
  "favorite_sports": ["football", "basketball"]
}
```

**Response:** `{ "success": true }`

---

### 5.3 PUT /user/notification-settings
Cập nhật cài đặt thông báo.

**Request body:**
```json
{
  "live_score": true,
  "match_reminder": true,
  "highlights": true,
  "favorite_teams_only": true
}
```

---

### 5.4 POST /user/reminders
Đặt nhắc nhở cho 1 trận.

**Request body:**
```json
{
  "match_id": "match-12345",
  "remind_before_minutes": 15
}
```

### 5.5 DELETE /user/reminders/{match_id}
Xóa nhắc nhở.

### 5.6 GET /user/reminders
Danh sách trận đã đặt nhắc.

---

### 5.7 DELETE /user/account
Xóa tài khoản (cần auth).

---

## 6. SEARCH

### 6.1 GET /search
Tìm kiếm đội, giải, trận.

**Query params:**
| Param | Type | Mô tả |
|-------|------|--------|
| q | string | Từ khóa (bắt buộc, min 2 ký tự) |
| type | string | `team`, `league`, `match`, `all` (mặc định `all`) |

**Response:**
```json
{
  "data": {
    "teams": [
      { "id": "man-utd", "name": "Manchester United", "logo_url": "...", "sport": "football" }
    ],
    "leagues": [
      { "id": "premier-league", "name": "Premier League", "logo_url": "...", "sport": "football" }
    ],
    "matches": []
  }
}
```

---

## 7. PUSH NOTIFICATIONS (Server → Client)

### 7.1 POST /user/fcm-token
Đăng ký FCM token.

**Request body:**
```json
{
  "token": "fcm-device-token",
  "platform": "android"
}
```

### Các loại push notification server gửi:

| Type | Khi nào | Payload |
|------|---------|---------|
| `match_start` | Trận bắt đầu | `{ match_id, home, away, league }` |
| `goal` | Có bàn thắng | `{ match_id, scorer, score, minute }` |
| `match_end` | Trận kết thúc | `{ match_id, final_score }` |
| `match_reminder` | 15 phút trước trận | `{ match_id, home, away, start_time }` |
| `highlight_new` | Highlight mới | `{ highlight_id, title, match_id }` |

---

## Tổng kết API

| # | Method | Endpoint | Mô tả |
|---|--------|----------|--------|
| 1 | GET | /sports | Danh sách môn |
| 2 | GET | /leagues | Danh sách giải |
| 3 | GET | /leagues/{id}/standings | Bảng xếp hạng |
| 4 | GET | /leagues/{id}/top-scorers | Vua phá lưới |
| 5 | GET | /matches/live | Trận đang live |
| 6 | GET | /matches/schedule | Lịch theo ngày |
| 7 | GET | /matches/results | Kết quả |
| 8 | GET | /matches/{id} | Chi tiết trận |
| 9 | GET | /matches/{id}/events | Sự kiện trận |
| 10 | GET | /matches/{id}/lineups | Đội hình |
| 11 | GET | /matches/{id}/h2h | Đối đầu |
| 12 | GET | /highlights | Danh sách highlight |
| 13 | GET | /articles | Danh sách bài viết |
| 14 | GET | /articles/{id} | Chi tiết bài viết |
| 15 | GET | /discover | Feed khám phá |
| 16 | GET | /teams/{id} | Thông tin đội |
| 17 | GET | /teams/{id}/matches | Trận của đội |
| 18 | GET | /user/profile | Profile user |
| 19 | PUT | /user/favorites | Cập nhật yêu thích |
| 20 | PUT | /user/notification-settings | Cài đặt thông báo |
| 21 | POST | /user/reminders | Đặt nhắc nhở |
| 22 | DELETE | /user/reminders/{match_id} | Xóa nhắc nhở |
| 23 | GET | /user/reminders | Danh sách nhắc |
| 24 | DELETE | /user/account | Xóa tài khoản |
| 25 | GET | /search | Tìm kiếm |
| 26 | POST | /user/fcm-token | Đăng ký push token |
