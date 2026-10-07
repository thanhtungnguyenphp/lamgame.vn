# Ghi chú View & Files — Luồng Mua/Download/Tài khoản (lamgame.vn)

> Tài liệu này ghi lại **file nào là bản ACTIVE** (được render thực tế) cho từng trang,
> để tránh sửa nhầm file trùng tên. Cập nhật: 2026-10-07.

## ⚠️ VẤN ĐỀ TRÙNG FILE (đọc trước khi sửa)

Dự án có NHIỀU bản trùng tên cho cùng một view. Thứ tự ưu tiên khi Laravel/Bagisto resolve:

1. **Theme `emsaigon`** (`resources/themes/emsaigon/views/...`) — ƯU TIÊN CAO NHẤT khi render qua HTTP (theme active). Nhiều component (header, account pages) lấy từ đây.
2. **Package active** `packages/Webkul/Shop/src/Resources/views/...` — map cho hint `shop::`. Dùng khi theme KHÔNG override.
3. **Package thừa** `packages/Shop/...` — KHÔNG được autoload (composer map `Webkul\Shop\` → `packages/Webkul/Shop`). **KHÔNG sửa file ở đây**, vô tác dụng.
4. Project views `resources/views/...` — layout custom lamgame.

**Cách xác định file active**: `view('ten.view')->getPath()` qua tinker, HOẶC test render qua HTTP kernel. KHÔNG tin vào việc chỉ sửa 1 bản.

## Môi trường
- Chạy Docker. Container PHP: **`lg-php`** (PHP 8.3). CLI host là PHP 8.2 → `php artisan` host bị chặn.
- Luôn chạy artisan qua: `docker exec lg-php php artisan ...`
- Sau khi sửa view/code: `docker exec lg-php php artisan view:clear` + `rm -f storage/framework/views/*.php`.
  Nếu sửa file trong `packages/` hoặc middleware → cân nhắc `docker restart lg-php` (xóa OPcache).

## Bản đồ view ACTIVE theo trang

| Trang | Route | File ACTIVE đang render |
|-------|-------|------------------------|
| Trang chủ | `shop.home.index` → `HomeController@indexV2` | `resources/views/home-v2/index.blade.php` |
| Chi tiết source | `lamgame.source-game.detail` | `resources/views/lamgame/pages/source-game-detail.blade.php` |
| Danh sách source | `lamgame.source-game` | `resources/views/lamgame/pages/source-game.blade.php` |
| Giỏ hàng | `shop.checkout.cart.index` | `resources/views/checkout/cart.blade.php` |
| Checkout | `shop.checkout.onepage.index` | `resources/views/checkout/onepage.blade.php` |
| Checkout success | `shop.checkout.onepage.success` | `resources/views/checkout/success.blade.php` |
| **Nav (header)** | — | `resources/themes/emsaigon/views/components/nav-redesign.blade.php` |
| Header desktop dropdown | — | `packages/Webkul/Shop/src/Resources/views/components/layouts/header/desktop/bottom.blade.php` |
| Header mobile | — | `packages/Webkul/Shop/src/Resources/views/components/layouts/header/mobile/index.blade.php` |
| **Layout tài khoản** | — | `resources/views/components/layouts/account.blade.php` (menu trái + dark theme) |
| Account dashboard (mặc định) | `shop.customers.account.index` → `CustomerController@account` | `resources/themes/emsaigon/views/customers/account/index.blade.php` |
| Thông tin cá nhân | `shop.customers.account.profile.index` → `CustomerController@index` | `resources/themes/emsaigon/views/customers/account/profile/index.blade.php` |
| Đơn hàng | `shop.customers.account.orders.index` | `resources/themes/emsaigon/views/customers/account/orders/index.blade.php` |
| Sản phẩm tải về | `shop.customers.account.downloadable_products.index` | `resources/themes/emsaigon/views/customers/account/downloadable_products/index.blade.php` |
| License của tôi | `lamgame.my-licenses` → `MyLicenseController` | `resources/views/lamgame/pages/my-licenses.blade.php` |

## Lưu ý kỹ thuật quan trọng (bài học từ các lần sửa)

1. **Theme emsaigon KHÔNG load Bagisto Vue.** Mọi component Vue của Bagisto bị HỎNG trong theme:
   - `<x-shop::datagrid>` → `<v-datagrid>` không mount → bảng TRỐNG (dù API trả data).
   - `<x-shop::form>` → `<v-form>` không submit → nút không hoạt động.
   - `<x-shop::modal>` → không mở.
   → GIẢI PHÁP: dùng **HTML + Blade thuần** (form POST + @csrf, bảng `<table>` query trực tiếp DB).

2. **Route account index gọi `account()`** (view `account.index`), KHÔNG phải `index()` (view `profile.index`). Dễ nhầm.

3. **CSRF**: đã thêm `auth/logout`, `customer/logout` vào except (`app/Http/Middleware/VerifyCsrfToken.php`) — logout không lỗi khi token hết hạn.

4. **Currency = USD toàn site**: base currency DB = USD; giá đọc từ `product_flat.price` (đã migrate USD từ `display_price_usd`). Helper `format_usd()` (`app/Helpers/price.php`). PayPal ép `currency_code='USD'` trong `SmartButtonController`.

5. **Logic post-purchase** (listener trên `sales.order.update-status.after`):
   - `GenerateLicenseOnOrderComplete` — sinh license (chỉ khi order có customer_id; dùng `item->product_id`/`item->type`, KHÔNG dùng `item->product` vì có thể null).
   - `CreateSellerEarningOnOrderComplete` — earning (chỉ sản phẩm có seller_id).
   - `ReverseEarningOnOrderCancel` — đảo earning khi cancel/refund.
   - `saveOrder` (PayPal) gắn customer vào cart nếu đã login mà cart là guest.

## Dọn dẹp đã hoàn tất (2026-10-07)
- Đã XÓA route debug `_debug/whoami`.
- Đã khôi phục giá `tower-defense-complete` (product#61) về $9.99.
