# Kế hoạch & Danh sách Task: Source Game + Mua/Download

> Tham chiếu phân tích: `01-ANALYSIS.md`. Mỗi task có mã, mức ưu tiên, mô tả, file liên quan, tiêu chí hoàn thành (DoD).
> Nguyên tắc: thay đổi nhỏ, có thể kiểm chứng; mỗi task 1 commit; luôn sửa trên `packages/Webkul/Shop` (bản active), không phải `packages/Shop`.

## Trạng thái
- [ ] Chưa làm · [~] Đang làm · [x] Xong

---

## Giai đoạn 1 — Bảo mật & tính năng gãy (ưu tiên cao nhất)

### [x] T1 — SEC-01/SEC-02: Khóa file version về private + validate upload
**Ưu tiên: 🔴 Cao nhất**
- Đổi `SellerVersionController::store` lưu disk `private` thay vì `public`.
- Thêm validate đuôi/MIME cho file version & `source_files.*` (allowlist: zip, rar, 7z, gz, tar). Chặn đuôi thực thi.
- Thêm route tải version có kiểm tra quyền sở hữu (seller) hoặc đã mua (buyer).
- **Files**: `app/Http/Controllers/SellerVersionController.php`, `SellerProductController.php`, (route mới trong `routes/web.php`).
- **DoD**: file version mới không truy cập được qua `/storage/...`; upload sai đuôi bị từ chối; tải version qua route có auth.

### [x] T2 — DATA-04: Sửa `auth_seller` null trong CheckSeller
**Ưu tiên: 🔴 Cao**
- Set `$request->auth_seller = $seller;` trong `CheckSeller` HOẶC đổi `SellerVersionController` sang `Auth::guard('customer')->user()->seller`.
- **Files**: `app/Http/Middleware/CheckSeller.php` hoặc `SellerVersionController.php`.
- **DoD**: trang quản lý version của seller hoạt động (không còn 404/null); ownership check đúng.

### [x] T3 — SEC-03/SEC-04: Chặn XSS khi render review & product card
**Ưu tiên: 🔴 Cao**
- Thêm hàm `escapeHtml()` phía client và escape mọi dữ liệu người dùng trước khi chèn `innerHTML` (review content/title/tên, product name/description).
- **Files**: `resources/views/lamgame/pages/source-game-detail.blade.php`, `resources/views/home-v2/index.blade.php`.
- **DoD**: review/product chứa `<img onerror>`/`<script>` hiển thị dưới dạng text, không thực thi.

### [x] T4 — FEAT-02/FEAT-06: Sửa form viết review (JS + guard)
**Ưu tiên: 🔴 Cao**
- Viết hàm `submitReview(productId)` gửi POST tới endpoint review.
- Thống nhất xác thực: đổi route review sang guard `web`/`customer` + CSRF (thay vì `auth:sanctum`) để khớp session customer, HOẶC cấp token.
- **Files**: partial review, `routes/api-reviews-hire.php` (hoặc route web mới), controller review.
- **DoD**: customer đăng nhập gửi được review; review vào DB với `status=pending`.

---

## Giai đoạn 2 — Entitlement sau mua (trọng tâm nghiệp vụ)

### [ ] T5 — FEAT-01: Đấu nối hệ thống License
**Ưu tiên: 🔴 Cao**
- Thêm listener trên order-complete gọi `LicenseService::generateAfterPurchase` để sinh license key cho người mua.
- Thêm trang "License của tôi" + route; đấu nối `getMyLicenses/verify`.
- **Files**: listener mới, `app/Services/LicenseService.php`, `app/Models/LicenseKey.php`, route + view.
- **DoD**: sau khi order `completed`, người mua có license key xem được trong tài khoản.

### [ ] T6 — FEAT-03: Hoàn tất đơn digital tự động
**Ưu tiên: 🟠 Cao**
- Xác minh `OnepageController::storeOrder` + Paypal callback; thêm cơ chế chuyển order digital sang `completed` khi thanh toán thành công (hoặc invoice+shipment tự động cho sản phẩm downloadable).
- **Files**: cần đọc `packages/Webkul/Shop/.../OnepageController`, cấu hình payment.
- **DoD**: mua xong + thanh toán thành công → order `completed` → tải được ngay; không kẹt `pending`.

---

## Giai đoạn 3 — An toàn giao dịch tiền

### [x] T7 — DATA-01: Earning idempotent + transaction
**Ưu tiên: 🔴 Cao**
- Bọc `createFromOrder` trong `DB::transaction`; thêm unique constraint `(order_item_id)` (migration); dùng `firstOrCreate`/`lockForUpdate`.
- Đọc phí từ `config/source-game-revenue.php` (DATA-05).
- **Files**: `app/Models/SourceGameEarning.php`, listener, migration mới, config.
- **DoD**: chạy listener 2 lần cho cùng order không tạo earning trùng; phí lấy từ config.

### [x] T8 — DATA-02/DATA-03: Rút tiền an toàn + thống nhất balance
**Ưu tiên: 🟠 Cao**
- Gộp logic `getAvailableBalance` vào 1 nơi (service/trait), trừ cả pending/processing.
- Bọc `SellerWithdrawalController::store` trong transaction + `lockForUpdate`, verify lại balance.
- **Files**: `app/Http/Controllers/SellerEarningController.php`, `SellerController.php`.
- **DoD**: 2 request rút đồng thời không vượt tổng số dư; dashboard & trang rút hiển thị cùng con số.

### [ ] T9 — DATA-06: Holdback + reverse earning khi refund
**Ưu tiên: 🟡 Trung bình**
- Thêm trạng thái `hold`/clearing cho earning; listener trừ/huỷ earning khi order bị cancel/refund.
- **Files**: `SourceGameEarning`, listener refund mới, migration (nếu cần cột).
- **DoD**: refund đơn đã completed → earning tương ứng bị đảo, số dư giảm.

---

## Giai đoạn 4 — UX catalog & hiệu năng

### [ ] T10 — PERF-01/PERF-02: Khử N+1 ở 2 luồng catalog
**Ưu tiên: 🟠 Cao**
- Trang list: lấy `product_flat` theo `whereIn(product_id, ids)` 1 lần + cache file-exists.
- API homepage: gộp query ảnh đại diện.
- **Files**: `LamGamePageController::sourceGame`, `app/Services/HomepageV2Service.php`.
- **DoD**: số query không tăng tuyến tính theo số sản phẩm (kiểm bằng debugbar/log).

### [ ] T11 — QUAL-01: Hoàn thiện/dọn filter trang /source-game
**Ưu tiên: 🟡 Trung bình**
- Xử lý `engine/platform/pricing` trong query; map `sort=popular/featured`; hoặc gỡ control chưa hỗ trợ.
- **Files**: `LamGamePageController::sourceGame`, `resources/views/lamgame/pages/source-game.blade.php`.
- **DoD**: mọi filter hiển thị đều có tác dụng thực; không còn filter "giả".

### [ ] T12 — FEAT-04/FEAT-05/QUAL-03: Sửa video, phân trang review, dọn code chết
**Ưu tiên: 🟡 Trung bình**
- Dùng iframe cho YouTube/Vimeo; thêm "xem thêm" review; gỡ `production_ready`, `*-partial`, "25 MB" bịa.
- **Files**: `source-game-detail.blade.php`.
- **DoD**: video demo phát được; xem được >10 review; không còn biến/khối chết.

---

## Giai đoạn 5 — Dọn dẹp & phòng thủ

### [ ] T13 — SEC-06: Zip-Slip + sandbox demo
**Ưu tiên: 🟡 Trung bình**
- Validate tên entry khi giải nén ZIP demo (chặn `../`); phục vụ demo không `allow-same-origin` hoặc domain riêng.
- **Files**: `DemoController::store`, `source-game/demo.blade.php`.
- **DoD**: ZIP chứa path traversal bị từ chối; demo không truy cập được cookie same-origin.

### [ ] T14 — SEC-05: Scope seller cho API manage downloadable-links
**Ưu tiên: 🟠 Cao (cần xác minh trước)**
- Xác minh thực tế rồi thêm kiểm tra `product.seller_id` khớp caller.
- **Files**: `ProductManageController`, `routes/api-ecommerce-manage.php`.
- **DoD**: caller chỉ thao tác được downloadable-link của sản phẩm mình sở hữu.

---

## Thứ tự thực thi đề xuất
1. T2 → T1 (seller/version an toàn + hoạt động)
2. T3 → T4 (review: XSS + form)
3. T7 → T8 (tiền an toàn)
4. T5 → T6 (entitlement/license + hoàn tất đơn)
5. T10 → T11 → T12 (catalog/UX/hiệu năng)
6. T14 → T13 → T9 (phòng thủ còn lại)

> Mỗi task hoàn thành: chạy kiểm chứng (lint/test thủ công), commit riêng với mã task trong message, cập nhật checkbox ở file này.
