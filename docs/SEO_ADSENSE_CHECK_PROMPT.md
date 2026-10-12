# Prompt kiểm tra SEO & AdSense readiness — lamgame.vn

> Dùng cho agent MCP (có bộ công cụ SEO: `seo_health_report`, `seo_check_url`,
> `seo_check_robots`, `seo_generate_sitemap`, `seo_push_indexnow`, `seo_auto_index`).
> Mục tiêu: xác minh website đã sẵn sàng để Google AdSense phê duyệt và chỉ số
> lập chỉ mục (indexing) khỏe mạnh, sau đợt tối ưu "Phương án C" (noindex trang
> giá trị thấp).

---

## 0. Bối cảnh (đọc trước khi kiểm tra)

- Website: `https://lamgame.vn` — marketplace source game + blog + forum + mini-game + jobs.
- Nội dung chủ lực: **blog** (224 bài, phần lớn > 1500 ký tự) — đây là phần AdSense đánh giá.
- Đã áp dụng **Phương án C** để chuẩn bị AdSense (commit `8d5e985`):
  - `noindex` trang chơi mini-game `/choi-game/{slug}` (iframe, không nội dung).
  - `noindex` **có điều kiện** cho forum bài mỏng (< 200 ký tự).
  - `robots.txt` chặn `/games/` (asset game HTML thô).
  - **Giữ index**: blog, mini-game list `/choi-game`, forum bài đầy đủ, `/cong-dong`,
    jobs, source-game, trang pháp lý.
- AdSense publisher: `ca-pub-5812352607411986`. File `ads.txt` tại root đã đúng.
- Trạng thái AdSense: đã nộp đơn, nhận phản hồi "cần một vài điều chỉnh".

Nguyên tắc quan trọng: **trang đã `noindex` thì KHÔNG được chặn trong robots.txt**
(nếu chặn crawl, Google không đọc được thẻ noindex). Khi kiểm tra, phát hiện mâu
thuẫn này phải báo lỗi.

---

## 1. Kiểm tra Sitemap

1. Gọi `seo_health_report` → xác nhận tất cả sitemap tồn tại, kích thước > 0, mới cập nhật.
2. Gọi `seo_check_url` cho `https://lamgame.vn/sitemap.xml` → HTTP 200, `Content-Type` XML.
3. Mở sitemap index, xác nhận trỏ tới các sitemap con: pages, blogs, forum, source-game,
   jobs, landing, sellers.
4. Lấy ngẫu nhiên 3–5 URL từ `sitemap-blogs.xml` và `sitemap-source-game.xml`,
   gọi `seo_check_url` từng URL → tất cả phải **HTTP 200** (không 404/301/5xx).

**PASS khi:** mọi sitemap 200, URL mẫu 200, không có URL noindex nào bị liệt kê trong sitemap.

**Kiểm tra ngược (quan trọng):** sitemap **KHÔNG** được chứa URL `/choi-game/{slug}`
(trang đã noindex). Nếu có → báo lỗi "URL noindex lọt vào sitemap".

---

## 2. Kiểm tra robots.txt

1. Gọi `seo_check_robots` cho các path sau và xác nhận kết quả mong đợi:

| Path | Mong đợi |
|---|---|
| `/blog` | **Cho phép** crawl (Allow) |
| `/blog/huong-dan-unity-2023-tinh-nang-moi` | **Cho phép** |
| `/choi-game` (list) | **Cho phép** (giữ index) |
| `/cong-dong` | **Cho phép** |
| `/ads.txt` | **Cho phép** |
| `/app-ads.txt` | **Cho phép** |
| `/games/` | **Chặn** (Disallow) |
| `/api/` | **Chặn** |
| `/admin/` | **Chặn** |
| `/checkout/` | **Chặn** |

2. Xác nhận robots.txt **KHÔNG** chặn `/choi-game/` và `/forum/` (vì các trang này
   dùng thẻ `noindex`, cần crawl được để Google đọc thẻ).
3. Xác nhận có dòng `Sitemap: https://lamgame.vn/sitemap.xml`.

**PASS khi:** đúng toàn bộ bảng trên + không mâu thuẫn noindex/Disallow.

---

## 3. Kiểm tra thẻ meta robots (noindex Phương án C)

Gọi `seo_check_url` (hoặc fetch HTML) và kiểm tra thẻ `<meta name="robots">`:

| URL | Mong đợi |
|---|---|
| `https://lamgame.vn/` (trang chủ) | **KHÔNG** có noindex (index) |
| `https://lamgame.vn/blog` | **KHÔNG** noindex |
| 1 URL blog chi tiết bất kỳ | **KHÔNG** noindex |
| `https://lamgame.vn/choi-game` (list) | **KHÔNG** noindex |
| 1 URL `https://lamgame.vn/choi-game/{slug}` hợp lệ | **CÓ** `noindex, follow` |
| `https://lamgame.vn/source-game` | **KHÔNG** noindex |
| Trang pháp lý (`/chinh-sach-bao-mat`, `/dieu-khoan-su-dung`) | **KHÔNG** noindex |

Ghi chú: forum bài mỏng (< 200 ký tự) sẽ `noindex`, bài đầy đủ thì index — tùy bài.

**PASS khi:** đúng bảng trên; đặc biệt mini-game **play** phải noindex còn mini-game
**list** và blog phải index.

---

## 4. Kiểm tra AdSense readiness

1. `seo_check_url` cho `https://lamgame.vn/ads.txt`:
   - HTTP 200, `Content-Type: text/plain`.
   - Nội dung đúng: `google.com, pub-5812352607411986, DIRECT, f08c47fec0942fa0`
   - Không BOM, không dòng trống thừa.
2. Fetch HTML trang chủ → xác nhận có meta
   `<meta name="google-adsense-account" content="ca-pub-5812352607411986">`.
3. Xác nhận publisher ID trong `ads.txt` (`pub-5812352607411986`) **khớp** với meta
   (`ca-pub-5812352607411986`).
4. Trang pháp lý bắt buộc cho AdSense đều HTTP 200:
   - `/gioi-thieu`, `/lien-he`, `/chinh-sach-bao-mat`, `/dieu-khoan-su-dung`.
5. Trang `/lien-he` có thông tin liên hệ thật (email).
6. Footer trang chủ có link tới các trang pháp lý (điều hướng rõ ràng).

**PASS khi:** ads.txt đúng chuẩn + meta khớp + đủ trang pháp lý 200 + có liên hệ.

---

## 5. Kiểm tra chất lượng index (chống "lỗi index nghiêm trọng")

Với 5–8 URL quan trọng (trang chủ, 2–3 blog, source-game list, 1 source-game detail,
1 jobs), kiểm tra từng URL:

- HTTP **200** (không 301 chuỗi, không 404, không 5xx).
- Có `<link rel="canonical">` trỏ đúng chính nó (self-canonical).
- `<title>` và `<meta name="description">` tồn tại, không trống.
- **KHÔNG** có noindex (trừ các trang Mục 3 cố ý noindex).

**PASS khi:** tất cả URL quan trọng 200 + self-canonical + có title/description + index được.

---

## 6. Hành động sau kiểm tra (nếu tất cả PASS)

1. Gọi `seo_generate_sitemap` để tạo lại sitemap mới nhất.
2. Gọi `seo_push_indexnow` (hoặc `seo_auto_index`) để báo Google/Bing crawl lại —
   ưu tiên các URL blog chất lượng.
3. Ghi lại kết quả: URL đã đẩy, HTTP response.

---

## 7. Định dạng báo cáo kết quả

Trả về báo cáo theo mẫu:

```
## KẾT QUẢ KIỂM TRA SEO & ADSENSE — lamgame.vn

### 1. Sitemap: PASS / FAIL
- (chi tiết)

### 2. robots.txt: PASS / FAIL
- (bảng path + kết quả)

### 3. Meta robots (noindex Phương án C): PASS / FAIL
- (bảng URL + có/không noindex)

### 4. AdSense readiness: PASS / FAIL
- ads.txt: ...
- meta adsense: ...
- trang pháp lý: ...

### 5. Chất lượng index: PASS / FAIL
- (URL + status + canonical + noindex?)

### 6. Hành động đã thực hiện
- sitemap regenerate: ...
- indexnow push: ... URL

### TỔNG KẾT
- Sẵn sàng AdSense review? CÓ / CHƯA
- Vấn đề cần sửa (nếu có): ...
- Khuyến nghị bước tiếp theo: ...
```

---

## 8. Tiêu chí "Sẵn sàng yêu cầu AdSense xem xét lại"

Chỉ khuyến nghị bấm "Request review" trong AdSense khi:
- [ ] Mục 1–5 đều PASS.
- [ ] Mini-game play đã noindex; blog + trang giá trị cao vẫn index.
- [ ] Google đã có thời gian re-crawl (thường 1–3 ngày sau khi noindex được đẩy).
- [ ] Không còn URL noindex nào nằm trong sitemap.
- [ ] ads.txt + meta AdSense khớp publisher ID.

Nếu còn FAIL ở bất kỳ mục nào → liệt kê cụ thể để sửa trước, **chưa** request review.
