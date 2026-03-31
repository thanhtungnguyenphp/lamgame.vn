# SportPulse – Tài liệu sản phẩm chi tiết

## 1. Tổng quan

**SportPulse** – Nhịp đập thể thao. App xem tỉ số trực tiếp, lịch thi đấu, highlight video và bình luận tổng kết cho các môn thể thao phổ biến toàn cầu.

**Đối tượng:** Người yêu thể thao, đặc biệt bóng đá, bóng rổ, tennis, MMA/UFC, esports.

**Ngôn ngữ:** Tiếng Việt (mặc định), hỗ trợ tiếng Anh sau.

---

## 2. Các môn thể thao hỗ trợ (ưu tiên)

| Ưu tiên | Môn | Giải đấu chính |
|---------|-----|----------------|
| 🥇 1 | Bóng đá | Premier League, La Liga, Serie A, Bundesliga, Ligue 1, Champions League, V-League, World Cup |
| 🥈 2 | Bóng rổ | NBA, EuroLeague |
| 🥉 3 | Tennis | Grand Slam (AO, RG, Wimbledon, US Open), ATP/WTA |
| 4 | MMA/Võ thuật | UFC, ONE Championship |
| 5 | Esports | League of Legends (Worlds, LCK, VCS), Valorant, DOTA2 TI |

---

## 3. Mapping: File cũ (Lottery) → File mới (Sport)

### 3.1 XÓA (không dùng)

| File cũ | Lý do |
|---------|-------|
| `screens/dream_book/` | Sổ mơ – không liên quan |
| `screens/save_number/` | Ghi số xổ số |
| `screens/my_numbers/` | Số của tôi |
| `screens/vietlot/` | Vietlot |
| `screens/statistics/` | Thống kê xổ số |
| `models/lottery_result.dart` | Model xổ số |
| `models/lottery_schedule.dart` | Lịch xổ số |
| `models/vietlot_result.dart` | Kết quả Vietlot |
| `models/province_mapping.dart` | Mapping tỉnh thành |
| `models/saved_number.dart` | Số đã lưu |
| `models/scanned_ticket.dart` | Vé scan |
| `models/server_ticket.dart` | Vé server |
| `models/check_result.dart` | Kết quả dò vé |
| `models/statistics_result.dart` | Thống kê |
| `models/sync_result.dart` | Đồng bộ |
| `providers/lottery_provider.dart` | Provider xổ số |
| `providers/saved_numbers_provider.dart` | Provider số lưu |
| `providers/statistics_provider.dart` | Provider thống kê |
| `services/lottery_api_service.dart` | API xổ số |
| `services/number_matching_service.dart` | Dò số |
| `services/ticket_scanner_service.dart` | Scan vé |
| `widgets/lottery_result_card.dart` | Card xổ số |
| `widgets/camera_overlay_screen.dart` | Camera scan |
| `assets/dream_book.json` | Data sổ mơ |

### 3.2 GIỮ & TÁI SỬ DỤNG

| File cũ | Dùng cho |
|---------|----------|
| `main.dart` | Entry point – giữ nguyên |
| `app.dart` | Router – refactor navigation mới |
| `core/theme/app_theme.dart` | Theme – đổi màu mới |
| `core/constants/app_constants.dart` | Constants – đã cập nhật |
| `core/services/ad_service.dart` | Quảng cáo – giữ |
| `core/services/premium_service.dart` | Premium – giữ |
| `core/services/push_notification_service.dart` | Push notification – giữ |
| `core/services/local_storage_service.dart` | Local storage – giữ |
| `core/services/device_id_service.dart` | Device ID – giữ |
| `core/services/review_service.dart` | In-app review – giữ |
| `core/services/home_widget_service.dart` | Home widget – refactor cho sport |
| `core/utils/date_formatter.dart` | Format ngày – giữ |
| `core/utils/share_image_helper.dart` | Chia sẻ – giữ |
| `models/api_response.dart` | Response wrapper – giữ |
| `models/user_profile.dart` | User profile – giữ |
| `providers/auth_provider.dart` | Auth – giữ |
| `providers/premium_provider.dart` | Premium – giữ |
| `screens/auth/` | Đăng nhập – giữ |
| `screens/onboarding/` | Onboarding – refactor nội dung |
| `screens/premium/` | Paywall – giữ |
| `screens/profile/` | Profile, notifications, delete account – giữ |
| `widgets/banner_ad_widget.dart` | Banner quảng cáo – giữ |
| `firebase_options.dart` | Firebase config – giữ |

---

## 4. Màn hình chi tiết

### 4.1 Bottom Navigation (4 tabs)

```
┌─────────┬──────────┬────────────┬─────────┐
│ 🏠 Home │ 📅 Lịch  │ 🎬 Khám phá│ 👤 Tôi  │
└─────────┴──────────┴────────────┴─────────┘
```

---

### TAB 1: 🏠 HOME (Trang chủ)
**Route:** `/home`
**File:** `screens/home/home_screen.dart`

**Mục đích:** Dashboard tổng hợp – trận đang diễn ra, sắp diễn ra, kết quả mới nhất.

**Layout (scroll dọc):**

```
┌─────────────────────────────────┐
│ 🔴 TRẬN ĐANG DIỄN RA (Live)    │  ← Horizontal scroll
│ ┌───────────┐ ┌───────────┐    │
│ │ MU 2-1 ARS│ │ BAR 0-0 RM│    │
│ │ ⚽ 67'     │ │ ⚽ 23'     │    │
│ └───────────┘ └───────────┘    │
├─────────────────────────────────┤
│ ⏰ SẮP DIỄN RA (Hôm nay)       │
│ ┌─────────────────────────────┐ │
│ │ Liverpool vs Chelsea  20:00 │ │
│ │ Lakers vs Warriors    09:00 │ │
│ └─────────────────────────────┘ │
├─────────────────────────────────┤
│ 🎬 HIGHLIGHT MỚI                │  ← Horizontal scroll
│ ┌──────┐ ┌──────┐ ┌──────┐     │
│ │ 📹   │ │ 📹   │ │ 📹   │     │
│ │ MU   │ │ BAR  │ │ NBA  │     │
│ └──────┘ └──────┘ └──────┘     │
├─────────────────────────────────┤
│ ✅ KẾT QUẢ GẦN ĐÂY             │
│ │ MU 3-1 Fulham     FT        │ │
│ │ Lakers 112-108 Celtics FT   │ │
└─────────────────────────────────┘
```

**Widgets cần tạo:**
- `live_matches_section.dart` – Danh sách trận live, auto-refresh 30s
- `upcoming_matches_section.dart` – Trận sắp diễn ra hôm nay
- `highlights_section.dart` – Horizontal list video highlight
- `recent_results_section.dart` – Kết quả gần đây

---

### TAB 2: 📅 LỊCH THI ĐẤU
**Route:** `/schedule`
**File:** `screens/schedule/schedule_screen.dart`

**Mục đích:** Xem lịch thi đấu theo ngày, lọc theo môn/giải.

**Layout:**

```
┌─────────────────────────────────┐
│ ◀ T2 28/03 │ T3 29/03 │ T4 ▶  │  ← Date picker horizontal
├─────────────────────────────────┤
│ [⚽ Tất cả] [🏀] [🎾] [🥊]     │  ← Filter chips
├─────────────────────────────────┤
│ ── Premier League ──            │
│ │ MU vs Arsenal       20:00   ││
│ │ Liverpool vs Chelsea 22:30   ││
│ ── La Liga ──                   │
│ │ Barcelona vs Real    02:00   ││
│ ── NBA ──                       │
│ │ Lakers vs Warriors   09:00   ││
├─────────────────────────────────┤
│ 🔔 Nhấn vào trận để bật nhắc   │
└─────────────────────────────────┘
```

**Chức năng:**
- Chọn ngày (±7 ngày)
- Filter theo môn thể thao
- Filter theo giải đấu
- Nhấn vào trận → đi tới chi tiết trận (match detail)
- Bật reminder cho trận sắp tới (local notification)

---

### TAB 3: 🎬 KHÁM PHÁ (Discover)
**Route:** `/discover`
**File:** `screens/discover/discover_screen.dart`

**Mục đích:** Video highlight, bình luận, tổng kết trận đấu.

**Layout:**

```
┌─────────────────────────────────┐
│ [🔥 Trending] [⚽] [🏀] [🎾]   │  ← Filter tabs
├─────────────────────────────────┤
│ ┌─────────────────────────────┐ │
│ │ 📹 Video thumbnail          │ │
│ │ ▶ 2:35                      │ │
│ ├─────────────────────────────┤ │
│ │ MU 3-1 Fulham | Highlights  │ │
│ │ 👁 12.5K  •  2 giờ trước    │ │
│ └─────────────────────────────┘ │
│ ┌─────────────────────────────┐ │
│ │ 📝 Tổng kết vòng 30 EPL     │ │
│ │ Bài viết • 5 phút đọc       │ │
│ └─────────────────────────────┘ │
│ ┌─────────────────────────────┐ │
│ │ 📹 Lakers vs Warriors       │ │
│ │ ▶ 3:12                      │ │
│ └─────────────────────────────┘ │
└─────────────────────────────────┘
```

**Loại nội dung:**
1. **Video highlight** – Clip 1-5 phút tổng hợp bàn thắng, pha đẹp
2. **Bài tổng kết** – Text ngắn + ảnh, tóm tắt trận/vòng đấu
3. **Bình luận nhanh** – Đánh giá ngắn gọn sau trận

---

### TAB 4: 👤 TÔI (Profile)
**Route:** `/profile`
**File:** `screens/profile/profile_screen.dart` (giữ từ cũ)

**Chức năng:**
- Thông tin tài khoản (avatar, tên, email)
- ⭐ Đội yêu thích (chọn đội để ưu tiên hiển thị)
- 🔔 Cài đặt thông báo (theo giải, theo đội)
- 🌙 Dark mode toggle
- 👑 Nâng cấp Premium (bỏ quảng cáo)
- 📋 Điều khoản & Chính sách
- 🗑 Xóa tài khoản
- 🚪 Đăng xuất

---

### MÀN HÌNH PHỤ (không nằm trong tab)

#### 4.5 Chi tiết trận đấu (Match Detail)
**Route:** `/match/:id`
**File:** `screens/match_detail/match_detail_screen.dart`

```
┌─────────────────────────────────┐
│ ← Back              🔔 ↗ Share │
├─────────────────────────────────┤
│        Premier League           │
│     ┌─────┐     ┌─────┐        │
│     │ MU  │  2-1│ ARS │        │
│     └─────┘     └─────┘        │
│        ⚽ 67' • Đang diễn ra    │
├─────────────────────────────────┤
│ [Tổng quan] [Đội hình] [H2H]   │
├─────────────────────────────────┤
│ ⚽ 23' Rashford (MU)            │
│ 🟨 35' Saliba (ARS)            │
│ ⚽ 45' Saka (ARS)              │
│ ⚽ 67' Bruno (MU)              │
├─────────────────────────────────┤
│ 🎬 Highlight liên quan          │
│ ┌──────┐ ┌──────┐              │
│ │ 📹   │ │ 📹   │              │
│ └──────┘ └──────┘              │
└─────────────────────────────────┘
```

**Tabs trong match detail:**
- **Tổng quan:** Sự kiện trận (bàn thắng, thẻ, thay người), thống kê (sở hữu bóng, sút, phạt góc...)
- **Đội hình:** Sơ đồ đội hình 2 đội
- **H2H:** Lịch sử đối đầu, phong độ 5 trận gần nhất

#### 4.6 Chi tiết giải đấu (League Detail)
**Route:** `/league/:id`
**File:** `screens/league/league_detail_screen.dart`

- Bảng xếp hạng (standings)
- Lịch thi đấu của giải
- Vua phá lưới / Top scorer

#### 4.7 Video Player
**Route:** `/video/:id`
**File:** `screens/video/video_player_screen.dart`

- Phát video highlight (embed YouTube hoặc stream URL)
- Thông tin trận đấu liên quan
- Video liên quan bên dưới

#### 4.8 Onboarding (refactor)
**File:** `screens/onboarding/onboarding_screen.dart`

3 slides:
1. "Tỉ số trực tiếp" – Theo dõi mọi trận đấu real-time
2. "Không bỏ lỡ" – Lịch thi đấu & nhắc nhở thông minh
3. "Highlight & Tổng kết" – Xem lại pha đẹp, đọc bình luận

→ Chọn môn thể thao yêu thích → Chọn đội yêu thích → Vào app

---

## 5. Models mới

```
lib/models/
├── api_response.dart          ← GIỮ
├── user_profile.dart          ← GIỮ (thêm field favorite_teams, favorite_sports)
├── sport.dart                 ← MỚI: enum Sport { football, basketball, tennis, mma, esports }
├── league.dart                ← MỚI: id, name, sport, country, logoUrl, season
├── team.dart                  ← MỚI: id, name, shortName, logoUrl, sport, leagueId
├── match.dart                 ← MỚI: id, homeTeam, awayTeam, league, startTime, status, score, minute
├── match_event.dart           ← MỚI: id, matchId, type(goal/card/sub), minute, player, team
├── match_stats.dart           ← MỚI: possession, shots, corners, fouls...
├── lineup.dart                ← MỚI: matchId, team, formation, players[]
├── standing.dart              ← MỚI: leagueId, team, played, won, drawn, lost, gf, ga, points, rank
├── highlight.dart             ← MỚI: id, matchId, title, thumbnailUrl, videoUrl, duration, viewCount, createdAt
├── article.dart               ← MỚI: id, title, summary, imageUrl, content, type(recap/preview/opinion), createdAt
└── h2h.dart                   ← MỚI: team1, team2, matches[], team1Wins, team2Wins, draws
```

---

## 6. Providers mới

```
lib/providers/
├── auth_provider.dart         ← GIỮ
├── premium_provider.dart      ← GIỮ
├── live_matches_provider.dart ← MỚI: stream trận live, auto-refresh
├── schedule_provider.dart     ← MỚI: lịch thi đấu theo ngày
├── match_detail_provider.dart ← MỚI: chi tiết 1 trận
├── standings_provider.dart    ← MỚI: bảng xếp hạng
├── highlights_provider.dart   ← MỚI: danh sách highlight
├── discover_provider.dart     ← MỚI: feed khám phá (video + bài viết)
├── favorites_provider.dart    ← MỚI: đội/giải yêu thích
└── reminder_provider.dart     ← MỚI: nhắc nhở trận đấu
```

---

## 7. Services mới

```
lib/core/services/
├── ad_service.dart                ← GIỮ
├── premium_service.dart           ← GIỮ
├── push_notification_service.dart ← GIỮ
├── local_storage_service.dart     ← GIỮ
├── device_id_service.dart         ← GIỮ
├── review_service.dart            ← GIỮ
├── home_widget_service.dart       ← REFACTOR: hiển thị tỉ số thay vì xổ số
├── sport_api_service.dart         ← MỚI: thay lottery_api_service.dart
├── reminder_service.dart          ← MỚI: local notification nhắc trận
└── share_service.dart             ← REFACTOR từ share_image_helper
```
