# Phân tích hiện trạng: Chức năng Source Code Game & Mua/Download

> Tài liệu phân tích hiện trạng (as-is) cho luồng **mua source game** và **download source game** trên lamgame.vn.
> Ngày lập: 2026-10-03. Phạm vi khảo sát: read-only (không sửa code trong giai đoạn phân tích).

## 1. Bối cảnh & mục tiêu

Website là marketplace bán **source code game** (Laravel + Bagisto). Người dùng đăng ký để **mua** và **download** source; seller **đăng/upload** source để bán và nhận hoa hồng. Chức năng mua/download hiện **chưa hoàn thiện tốt**. Mục tiêu của đợt này là **sửa lỗi và tối ưu**, tập trung vào:

- Luồng xem/mua source code game (catalog → chi tiết → giỏ hàng → thanh toán).
- Luồng download sau khi mua (entitlement/license, kiểm soát quyền tải).
- Luồng seller (upload, version, earning, rút tiền) ở mức ảnh hưởng trực tiếp tới mua/download.

## 2. Kiến trúc liên quan (đã xác minh)

- **Package Shop active**: `packages/Webkul/Shop` (namespace `Webkul\Shop\` được map trong `composer.json` gốc → `packages/Webkul/Shop/src`; provider `Webkul\Shop\Providers\ShopServiceProvider` đăng ký ở `bootstrap/providers.php:38`).
  - ⚠️ Thư mục `packages/Shop` tồn tại song song **cùng namespace** nhưng **KHÔNG được autoload** từ composer gốc → đây là bản thừa, **không được sửa nhầm** vào đây. (Cần xác minh lại từng file trước khi sửa.)
- **Sản phẩm source game** = Bagisto *downloadable product*.
- **File source bán kèm order**: lưu disk `private` (`storage/app/private/...`), KHÔNG web-reachable → luồng download chính an toàn ở mức file.
- **File version** (`SourceGameVersion`): lưu disk `public` → **lộ file** (xem SEC-01).
- Trang danh sách: `GET /source-game` → `LamGamePageController::sourceGame()`; chi tiết: `GET /source-game/{slug}` → `sourceGameDetail()`.
- Homepage (home-v2) gọi API riêng `GET /api/v1/homepage/products` → `HomepageApiController` → `HomepageV2Service`.
- Earning: event `sales.order.update-status.after` → `CreateSellerEarningOnOrderComplete` → `SourceGameEarning::createFromOrder()` (chỉ khi `STATUS_COMPLETED`).

## 3. Danh sách vấn đề (đã khảo sát)

Ký hiệu mức độ: 🔴 Nghiêm trọng · 🟠 Cao · 🟡 Trung bình · 🟢 Thấp.
Mỗi mục có mã để tham chiếu trong `02-TASKS.md`.

### 3.1. Bảo mật (SEC)

| Mã | Mức | Mô tả | Vị trí |
|----|-----|-------|--------|
| SEC-01 | 🟠 | Version source lưu disk `public` → ai cũng tải qua `/storage/...` mà không cần mua | `app/Http/Controllers/SellerVersionController.php:38` |
| SEC-02 | 🟠 | Upload version/source không validate loại/đuôi file (chỉ `file\|max`) → nguy cơ upload file thực thi | `SellerVersionController.php:30-33`, `SellerProductController::store` (rule `source_files.*`) |
| SEC-03 | 🔴 | XSS stored khi render review ở trang chi tiết: `innerHTML` với `r.content/r.title/first_name` không escape | `resources/views/lamgame/pages/source-game-detail.blade.php` (hàm `renderReviews`, ~dòng 470) |
| SEC-04 | 🟡 | XSS tiềm ẩn ở product card homepage (`${product.name/description}` vào `innerHTML`) | `resources/views/home-v2/index.blade.php` (hàm `renderProductCard`) |
| SEC-05 | 🔴 | API quản lý downloadable-links (`/api/manage/...`) chỉ chặn bằng `api.key`, không scope theo `seller_id` → sửa/xóa/xem file source của **bất kỳ** sản phẩm (IDOR ghi) | `api-ecommerce-manage.php`; `ProductManageController` upload/update/detail downloadable-link | *cần xác minh lại*
| SEC-06 | 🟡 | Demo ZIP giải nén không chống Zip-Slip + iframe `allow-same-origin`+`allow-scripts` | `app/Http/Controllers/DemoController.php::store`; `resources/views/source-game/demo.blade.php:35` |

> Lưu ý: luồng **download chính** (`DownloadableProductController::download`) đã kiểm tra `customer_id` (chống IDOR cơ bản), chặn `pending`, có quota — tương đối ổn. Rủi ro bảo mật tập trung ở **version upload** và **render review**.

### 3.2. Tính năng chưa hoàn thiện (FEAT)

| Mã | Mức | Mô tả | Vị trí |
|----|-----|-------|--------|
| FEAT-01 | 🔴 | **Hệ thống License tồn tại nhưng chưa được đấu nối**: `LicenseService::generateAfterPurchase`, `LicenseKey::generate` không bao giờ được gọi; `verify/getMyLicenses/transfer` không có route. Người mua không nhận được license key | `app/Services/LicenseService.php`, `app/Models/LicenseKey.php`, `ProductLicense.php`, `LicenseType.php` |
| FEAT-02 | 🔴 | **Form viết review hỏng**: `onsubmit` gọi `submitReview()` nhưng hàm này không tồn tại trong toàn repo → bấm Gửi không có tác dụng | `resources/views/lamgame/partials/source-game-reviews.blade.php:39` |
| FEAT-03 | 🟠 | Đơn hàng digital không tự hoàn tất: không có webhook/callback để chuyển order sang `completed`; `downloadable_link_purchased` tạo ở `pending` (chặn tải) trong khi earning chỉ tạo khi `completed` → người đã trả tiền có thể không bao giờ tải được | `storeOrder` (OnepageController), listener earning |
| FEAT-04 | 🟡 | Khối video gameplay ở trang chi tiết không bao giờ render (dùng biến `video_preview_mp4` chưa từng set; YouTube không phát qua thẻ `<video>`) | `source-game-detail.blade.php:87-96` |
| FEAT-05 | 🟡 | Phân trang review không dùng được (fetch cứng `per_page=10`, không có nút xem thêm) | `source-game-detail.blade.php:468` |
| FEAT-06 | 🟡 | Guard xác thực review không khớp: route review dùng `auth:sanctum` còn form hiển thị theo `@auth('customer')` (session) → kể cả có JS cũng không xác thực được | `routes/api-reviews-hire.php:22` vs partial |

### 3.3. Tính đúng đắn dữ liệu / nghiệp vụ (DATA)

| Mã | Mức | Mô tả | Vị trí |
|----|-----|-------|--------|
| DATA-01 | 🔴 | Earning: không transaction + chống trùng bằng check-then-insert (không atomic) → có thể tạo earning **trùng** (trả tiền 2 lần) nếu event bắn nhiều lần | `SourceGameEarning::createFromOrder`, `CreateSellerEarningOnOrderComplete` |
| DATA-02 | 🟠 | Rút tiền: không transaction/lock khi tính balance → race condition rút vượt số dư | `SellerWithdrawalController::store` (trong `SellerEarningController.php`) |
| DATA-03 | 🟠 | Hai công thức `getAvailableBalance` khác nhau giữa dashboard và withdrawal → số dư hiển thị sai | `SellerController` vs `SellerWithdrawalController` |
| DATA-04 | 🟠 | `SellerVersionController` dùng `request()->auth_seller` nhưng middleware `CheckSeller` **không set** biến này → luôn null → chức năng version **hỏng hoàn toàn** | `SellerVersionController.php:11,24`; `app/Http/Middleware/CheckSeller.php` |
| DATA-05 | 🟡 | Phí nền tảng hardcode `30.00`, bỏ qua `config/source-game-revenue.php` | `SourceGameEarning::createFromOrder` |
| DATA-06 | 🟡 | Earning set `completed` ngay, không holdback, không reverse khi refund/cancel → seller rút tiền của đơn đã hoàn | `SourceGameEarning`, thiếu listener refund |
| DATA-07 | 🟡 | Bất nhất trạng thái "đã mua": review check `status='completed'`, nơi khác tính `IN ('processing','completed')` | `SourceGameReviewService::create` vs catalog |

### 3.4. Hiệu năng & chất lượng code (PERF)

| Mã | Mức | Mô tả | Vị trí |
|----|-----|-------|--------|
| PERF-01 | 🟠 | N+1 ở `/source-game`: query `product_flat` cho **từng** sản phẩm trong vòng lặp + `file_exists()` mỗi sản phẩm | `LamGamePageController::sourceGame` (~dòng 1091, 1115) |
| PERF-02 | 🟠 | N+1 ở `HomepageV2Service::getProductThumbnail` (1 query ảnh/sản phẩm) | `app/Services/HomepageV2Service.php` (~dòng 385-410) |
| PERF-03 | 🟡 | Trang chi tiết mở `ZipArchive` quét readme/license mỗi request | `sourceGameDetail` (~dòng 1430) |
| QUAL-01 | 🟡 | Trang `/source-game` bỏ qua filter `engine/platform/pricing` (filter "giả"), `sort=popular/featured` rơi về default | `sourceGame` (~dòng 975-1058), `source-game.blade.php` |
| QUAL-02 | 🟡 | Hai luồng catalog (trang list vs homepage API) logic trùng nhưng lệch nhau → cùng sản phẩm hiển thị khác nhau | `LamGamePageController` vs `HomepageV2Service` |
| QUAL-03 | 🟢 | Code chết: biến `production_ready`, container review `*-partial`, suy luận engine bằng chuỗi tên + "25 MB" bịa | nhiều nơi trong `source-game-detail.blade.php`, `sourceGame` |

## 4. Nhận định tổng thể

Điểm yếu lớn nhất **không nằm ở bước đọc file download** (đã có check cơ bản), mà ở:

1. **Entitlement sau mua chưa hoàn thiện** (FEAT-01 license chưa đấu nối, FEAT-03 order digital không auto-complete) → người mua trả tiền nhưng trải nghiệm nhận hàng gãy.
2. **Lỗ hổng ở version upload** (SEC-01/SEC-02) → lộ source trả phí + nguy cơ upload file nguy hiểm.
3. **Tính tiền thiếu an toàn giao dịch** (DATA-01/02/03) → rủi ro trả trùng, rút vượt số dư.
4. **Review gãy đầu-cuối** (FEAT-02/06 + SEC-03) → chức năng đánh giá vừa không chạy vừa có XSS.

## 5. Điểm cần xác minh thêm trước khi fix từng task

- SEC-05: đọc trực tiếp `ProductManageController` + `api-ecommerce-manage.php` để xác nhận thiếu scope seller.
- FEAT-03: đọc `OnepageController::storeOrder` và cấu hình Paypal để xác định cơ chế hoàn tất đơn.
- QUAL-01: xác nhận cột `product_flat.engine/genre/platform/difficulty_level` có dữ liệu thực.
- Luôn kiểm tra file thuộc `packages/Webkul/Shop` (active), không phải `packages/Shop`.
