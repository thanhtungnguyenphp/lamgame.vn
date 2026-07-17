# SEO Tasks Phase 2 — lamgame.vn (16/07/2026)

> Phân tích từ Google Search Console — actions tiếp theo sau khi Phase 1 hoàn thành

---

## Task 1: Noindex tất cả blog tag/category listing pages

**Priority:** 🔴 High  
**Impact:** Giảm 68 pages "Duplicate, Google chose different canonical"  
**Effort:** Low

**Vấn đề:** Google crawl `/blog?tag=*` và `/blog?category=*`, rồi chọn `/blog` làm canonical. 68 pages bị đánh duplicate.

**Cần làm:**
- Thêm `<meta name="robots" content="noindex, follow">` cho TẤT CẢ pages có query param `tag` hoặc `category` (không chỉ pages rỗng)
- Hoặc thêm `<link rel="canonical" href="https://lamgame.vn/blog">` cho tất cả listing pages

**URLs mẫu bị ảnh hưởng:**
```
/blog?category=programming
/blog?tag=vong-loai-world-cup
/blog?category=jude-bellingham
/blog?tag=season-2026
/blog?tag=lol
/blog?tag=2d-art
/blog?tag=rank
/blog?tag=efootball
/blog?tag=counter-strike
/blog?tag=review
```

**Verify:**
```bash
curl -s https://lamgame.vn/blog?tag=lol | grep 'name="robots"'
# Phải trả: <meta name="robots" content="noindex, follow">
```

---

## Task 2: Review và sửa robots.txt

**Priority:** 🔴 High  
**Impact:** Tăng crawl budget, cho phép Google index thêm pages  
**Effort:** Low

**Vấn đề:** 95 pages bị "Blocked by robots.txt" — một số không nên block.

**Pages ĐANG bị block SAI (cần cho phép crawl):**
```
/viec-lam-game?page=3    ← pagination việc làm, nên cho crawl
/blog?page=31            ← pagination blog, nên cho crawl
```

**Pages block ĐÚNG (giữ nguyên):**
```
/api/*                   ← OK, API không cần index
```

**Cần làm:**
- Mở file `public/robots.txt`
- Bỏ rule block pagination: `/blog?page=`, `/viec-lam-game?page=`
- Giữ block: `/api/`, `/admin/`, `/checkout/`
- Đảm bảo không block `/source-game?cat=*` (nếu có)

**Verify:**
```bash
curl -s https://lamgame.vn/robots.txt
# Kiểm tra không có rule block /blog, /viec-lam-game
```

---

## Task 3: Redirect http://lamgame.vn/index.php

**Priority:** 🟡 Medium  
**Impact:** 1 URL + cleanup legacy  
**Effort:** Low

**Vấn đề:** `http://lamgame.vn/index.php` vẫn bị crawl và blocked by robots.txt. Nên redirect 301 về homepage.

**Cần làm:**
- Thêm redirect: `/index.php` → `https://lamgame.vn/` (301)

**Verify:**
```bash
curl -sI https://lamgame.vn/index.php | grep -i "HTTP\|location"
# Phải trả: 301 + location: https://lamgame.vn/
```

---

## Task 4: Tối ưu meta title/description cho /source-game

**Priority:** 🟡 Medium  
**Impact:** Tăng CTR cho keyword chủ lực (177 clicks/tháng, top page)  
**Effort:** Medium

**Dữ liệu hiện tại:**
- `/source-game`: 177 clicks, 871 impressions, CTR ~20%
- Top keywords: "source game", "mua source game", "sourcegame", "mã nguồn game"

**Cần làm:**
- Tối ưu `<title>` chứa keyword chính: "Source Game" + USP
- Tối ưu `<meta description>` chứa CTA + keywords phụ
- Thêm structured data (Product hoặc ItemList) nếu chưa có

**Gợi ý title:**
```
Mua Bán Source Game Unity, Unreal | Mã Nguồn Game Giá Rẻ — LamGame.vn
```

**Gợi ý description:**
```
Kho source game Unity, Unreal Engine đa dạng. Mua bán mã nguồn game 2D, 3D chất lượng cao, giá từ 99K. Hỗ trợ cài đặt miễn phí.
```

---

## Task 5: Tạo internal links đến pages "Discovered - not indexed"

**Priority:** 🟡 Medium  
**Impact:** Giúp Google crawl 54 pages đã discover nhưng chưa index  
**Effort:** Medium

**Vấn đề:** 54 pages Google đã phát hiện qua sitemap/links nhưng chưa bao giờ crawl. Nguyên nhân: crawl budget thấp, không có internal links mạnh.

**Cần làm:**
- Xác định 54 URLs này (export từ GSC)
- Thêm internal links từ pages có traffic cao (homepage, /source-game, /blog) đến các pages này
- Đảm bảo các pages này có trong sitemap
- Có thể thêm section "Bài viết liên quan" / "Xem thêm" ở cuối blog posts

---

## Task 6: Fix source-game category pages canonical

**Priority:** 🟢 Low  
**Impact:** Cleanup 204 "Alternate page with proper canonical"  
**Effort:** Low

**Vấn đề:** `/source-game?cat=unity`, `/source-game?cat=unreal`, `/source-game?cat=2d`, `/source-game?cat=3d` — Google coi là alternate của `/source-game`.

**Cần làm (chọn 1):**
- **Option A:** Nếu muốn index riêng từng category → thêm `<link rel="canonical" href="https://lamgame.vn/source-game?cat=unity">` (self-referencing canonical)
- **Option B:** Nếu không muốn index → thêm noindex hoặc canonical trỏ về `/source-game`

**Lưu ý:** 204 pages phần lớn là expected behavior (http→https duplicates, pagination). Không urgent.

---

## TỔNG KẾT ƯU TIÊN

| # | Task | Priority | Effort | Deadline |
|---|------|----------|--------|----------|
| 1 | Noindex all blog tag/category | 🔴 High | Low | Trong tuần |
| 2 | Review robots.txt | 🔴 High | Low | Trong tuần |
| 3 | Redirect /index.php | 🟡 Medium | Low | Trong tuần |
| 4 | Tối ưu meta /source-game | 🟡 Medium | Medium | Tuần sau |
| 5 | Internal links cho discovered pages | 🟡 Medium | Medium | Tuần sau |
| 6 | Source-game category canonical | 🟢 Low | Low | Khi tiện |

---

## SỐ LIỆU HIỆN TẠI (baseline)

```
Indexed:        407 pages
Not indexed:    987 pages (đang giảm do Phase 1 fix)
Clicks/3m:      316
Impressions/3m: 5,470
CTR:            5.8%
Position:       16.9
Desktop CWV:    33 good URLs, 0 poor
Mobile CWV:     Không đủ data
```

**Mục tiêu sau Phase 2 (2-4 tuần):**
- Not indexed giảm còn <700
- Indexed tăng lên 500+
- CTR cải thiện lên 7%+ (nhờ meta optimization)
