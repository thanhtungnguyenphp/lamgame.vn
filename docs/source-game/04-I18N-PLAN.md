# Giải pháp Đa ngôn ngữ (Việt / Anh / Đức) — lamgame.vn

> Tài liệu phân tích hiện trạng + đề xuất giải pháp thêm 3 ngôn ngữ: Tiếng Việt (vi),
> Tiếng Anh (en), Tiếng Đức (de). Ngày lập: 2026-10-07.
> **Chưa triển khai** — chờ quyết định hướng đi (xem mục "Cần quyết định" cuối tài liệu).

---

## 1. Hiện trạng (đã khảo sát)

| Thành phần | Tình trạng |
|---|---|
| Locale trong DB (`locales`) | Có **vi, en** — **CHƯA có `de`** |
| Locale mặc định channel | `vi` (channel `default`) |
| Cơ chế đổi ngôn ngữ | Bagisto: query `?locale=xx` → lưu **session** (key `locale`). **KHÔNG có prefix URL** `/en/`, `/de/` |
| Middleware xử lý locale | `packages/Webkul/Shop/src/Http/Middleware/Locale.php` (alias `locale`) |
| File dịch shop core | `packages/Webkul/Shop/src/Resources/lang/`: **`en/app.php` + `de/app.php` đã có đầy đủ** (1290 dòng). **THIẾU `vi/app.php`** → tiếng Việt shop core đang fallback về Anh |
| `lang/` (root) | Có nhiều locale nhưng chỉ chứa 4 file core Laravel (auth/validation/...), không chứa chuỗi UI shop |
| Sản phẩm (`product_flat`) | Có cột `locale`; đã có bản **vi (75 sp) + en (75 sp)**; chưa có **de** |
| View custom lamgame (`resources/views/lamgame`) | **93 file, ~2.632 dòng hard-code tiếng Việt, 0 dùng hàm dịch** ← khối lượng lớn nhất |
| Trang chủ (`home-v2/index.blade.php`) | Hard-code tiếng Việt, 0 hàm dịch |
| Theme emsaigon (`resources/themes/emsaigon`) | Dùng `@lang/__()` 164 lần nhưng còn 38/49 file hard-code |
| Layout master (`resources/views/layouts/master.blade.php`) | `<html lang="vi">` (dòng 2) + `og:locale=vi_VN` (dòng 27) **cố định**, không động theo locale |
| Middleware trên route custom | Route lamgame (home, source-game, forum...) **KHÔNG chạy middleware `locale`** → đổi ngôn ngữ không áp cho trang custom |

### Vấn đề cốt lõi
Hạ tầng Bagisto **đã hỗ trợ đa ngôn ngữ**, nhưng **toàn bộ phần custom lamgame hard-code tiếng Việt** → không dịch được. Đây là ~80% khối lượng công việc.

---

## 2. Các phương án

### Phương án A — Dịch thủ công bằng file lang (chuẩn Laravel/Bagisto) ✅ KHUYẾN NGHỊ
Chuẩn mực, bền vững, SEO tốt, kiểm soát chất lượng dịch.

Các bước:
1. **DB**: thêm locale `de` + gán vào channel. Tạo `vi/app.php` cho shop core (bù phần thiếu).
2. **Externalize chuỗi**: đưa text hard-code trong view ra file lang (`lang/vi,en,de/`), thay bằng `__('...')`. Thứ tự: layout/nav/footer (ảnh hưởng mọi trang) → home-v2 → source-game → các trang còn lại.
3. **Middleware**: thêm `locale` vào route custom lamgame.
4. **Layout động**: `<html lang="{{ app()->getLocale() }}">`, og:locale động.
5. **Language switcher**: nút 🇻🇳/🇬🇧/🇩🇪 trên header.
6. **Sản phẩm**: nhập/dịch bản `de` cho product_flat.

- Ưu: chuẩn, SEO tốt, kiểm soát chất lượng. Nhược: tốn công externalize ~2.600 dòng (chia nhiều đợt).

### Phương án B — Externalize + AI dịch tự động nội dung
Giống A nhưng dùng AI/script dịch hàng loạt vi→en→de cho file lang + product_flat, bạn rà soát lại.
- Ưu: nhanh hơn A về nội dung. Nhược: cần rà soát chất lượng (nhất là tiếng Đức).

### Phương án C — Dịch tự động client-side (Google Translate widget)
- Ưu: cực nhanh. Nhược: dịch máy, **SEO kém**, không chuyên nghiệp. **KHÔNG khuyến nghị** cho marketplace thương mại.

### Khuyến nghị
**Phương án A** (hoặc **A+B**: dùng AI dịch để tăng tốc nội dung nhưng externalize chuẩn). Vì đây là marketplace quốc tế (đã dùng USD) → cần chất lượng + SEO.

---

## 3. SEO đa ngôn ngữ (quyết định quan trọng)
Hiện dùng `?locale=` + session (cùng 1 URL cho mọi ngôn ngữ).
- **Lựa chọn 1 (đơn giản)**: giữ `?locale=` — nhanh, nhưng SEO đa ngôn ngữ yếu (Google khó index từng ngôn ngữ).
- **Lựa chọn 2 (chuẩn SEO)**: thêm prefix URL `/en/...`, `/de/...` + thẻ `hreflang` — nhiều việc hơn (sửa routing) nhưng chuẩn quốc tế.

---

## 4. Phạm vi công việc ước lượng (nếu chọn A/A+B)

| Nhóm | Khối lượng | Ghi chú |
|---|---|---|
| DB: thêm `de` + gán channel | Nhỏ | 1 lần |
| `vi/app.php` shop core | Trung bình | Bù phần thiếu |
| Layout + nav + footer | Nhỏ-TB | Ảnh hưởng mọi trang, làm trước |
| Externalize `home-v2` | TB | Trang chủ |
| Externalize `source-game` (list + detail) | TB | Luồng mua |
| Externalize 93 file `lamgame/pages` | **Lớn** | Chia nhiều đợt theo mức ưu tiên |
| Theme emsaigon (38 file còn hard-code) | TB | |
| Language switcher + middleware + layout động | Nhỏ | Hạ tầng |
| Dịch product_flat `de` | TB | Có thể dùng AI |

Đề xuất ưu tiên: hạ tầng (switcher/middleware/layout) + layout/nav/footer + home-v2 + source-game (luồng mua) trước; các trang phụ (blog/forum/jobs/learn/legal) làm sau theo đợt.

---

## 5. CẦN QUYẾT ĐỊNH (để lập kế hoạch chi tiết + triển khai)

1. **Chọn phương án**: A / A+B / C? (khuyến nghị A hoặc A+B)
2. **Nội dung dịch EN/DE**: bạn tự cung cấp bản dịch, hay để tôi dùng AI dịch rồi bạn rà soát?
3. **SEO**: dùng `?locale=` đơn giản, hay làm prefix URL `/en//de/` chuẩn SEO?
4. **Phạm vi đợt đầu**: làm toàn bộ, hay ưu tiên trang chính (home + source-game + checkout + account) trước, trang phụ sau?

Sau khi chốt, sẽ lập kế hoạch chi tiết (chia giai đoạn, file cụ thể, DoD) và triển khai từng phần có kiểm chứng — tránh làm ồ ạt gây lỗi.

---

## Phụ lục — vị trí kỹ thuật tham chiếu
- Middleware Locale: `packages/Webkul/Shop/src/Http/Middleware/Locale.php`
- Switcher gốc Bagisto: `packages/Webkul/Shop/src/Resources/views/components/layouts/header/desktop/top.blade.php:219`
- Lang shop core: `packages/Webkul/Shop/src/Resources/lang/<locale>/app.php`
- Layout cần sửa `html lang`: `resources/views/layouts/master.blade.php:2,27`
- Route custom (cần thêm middleware locale): `routes/web.php`
- Bảng DB: `locales`, `channel_locales`, `product_flat.locale`
