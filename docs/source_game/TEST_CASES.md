# Seller Marketplace — Test Cases

**Ngày:** 2026-03-27
**Base URL:** `https://lamgame.vn` (production) hoặc `https://lamgame.local` (local)
**Branch:** `feat/seller-marketplace`

---

## Chuẩn bị

### Tài khoản test

| Role | Đăng nhập | Ghi chú |
|------|-----------|---------|
| Customer | Đăng nhập tại `/auth/login` | Cần tài khoản customer thường |
| Seller | Customer đã đăng ký seller (status=active) | Dùng seller "Jerry Games" có sẵn |
| Admin | Đăng nhập tại `/admin/login` | Tài khoản admin |

### Chạy migration (nếu chưa)

```bash
docker exec lg-php php /var/www/html/artisan migrate --force
docker exec lg-php php /var/www/html/artisan config:clear
docker exec lg-php php /var/www/html/artisan route:clear
```

---

## Sprint A — Earning & Withdrawal

### TC-A1: Order → Earning tự động

**Mục đích:** Khi order hoàn thành, hệ thống tự tạo earning record cho seller.

**Bước thực hiện:**

1. Đăng nhập customer, vào `/source-game`
2. Chọn 1 source game có seller (ví dụ product ID=50)
3. Thêm vào giỏ hàng → Checkout → Thanh toán
4. Đăng nhập admin → `/admin/sales/orders`
5. Mở order vừa tạo → Tạo Invoice → Tạo Shipment (hoặc chỉ Invoice nếu downloadable)
6. Kiểm tra order status chuyển sang "Completed"

**Verify:**

```bash
# Kiểm tra earning đã tạo
docker exec lg-php php /var/www/html/artisan tinker --execute="
\$e = \App\Models\SourceGameEarning::latest()->first();
echo json_encode(\$e?->toArray(), JSON_PRETTY_PRINT);
"
```

**Expected:**
- Earning record được tạo với `status=completed`
- `platform_fee_percent=30`, `seller_amount=70%` của order amount
- Seller stats (`total_sales`, `total_revenue`) tăng

**Nếu chưa có order thật, test bằng tinker:**

```bash
docker exec lg-php php /var/www/html/artisan tinker --execute="
// Giả lập tạo earning cho product có seller
\$order = \Webkul\Sales\Models\Order::latest()->first();
if(\$order) {
    \App\Models\SourceGameEarning::createFromOrder(\$order);
    echo 'Created earnings for order #' . \$order->id;
} else {
    echo 'No orders found — cần tạo order trước';
}
"
```

---

### TC-A2: Admin duyệt rút tiền

**Mục đích:** Admin có thể xem, duyệt, hoàn thành, từ chối yêu cầu rút tiền.

**Bước thực hiện:**

1. **Tạo withdrawal request (seller):**
   - Đăng nhập customer (seller) → `/seller/withdrawals/create`
   - Nhập số tiền (≥ 100,000đ) → Submit
   - Verify: redirect về `/seller/withdrawals` với status "Chờ xử lý"

2. **Admin xem danh sách:**
   - Đăng nhập admin → `/admin/withdrawals`
   - Verify: thấy withdrawal vừa tạo, status "Chờ duyệt", stats hiển thị đúng

3. **Admin duyệt:**
   - Click "Duyệt" trên withdrawal
   - Verify: status chuyển sang "Đang xử lý"

4. **Admin hoàn thành:**
   - Click "Hoàn thành" → nhập mã giao dịch (ví dụ: `TXN123456`)
   - Verify: status "Hoàn thành", `transaction_id` được lưu

5. **Admin từ chối (test riêng):**
   - Tạo withdrawal mới → Admin click "Từ chối" → nhập lý do
   - Verify: status "Từ chối", `admin_note` được lưu

**Verify bằng DB:**

```bash
docker exec lg-php php /var/www/html/artisan tinker --execute="
\App\Models\SourceGameWithdrawal::latest(5)->get(['id','amount','status','admin_note','transaction_id'])->each(function(\$w){
    echo \"#{\$w->id} {\$w->amount}đ status={\$w->status} note={\$w->admin_note} txn={\$w->transaction_id}\n\";
});
"
```

**Lưu ý:** Nếu seller chưa có earning, cần tạo earning trước (TC-A1) để có số dư rút tiền.

---

## Sprint B — Profile, Reviews, Wishlist

### TC-B1: Seller Profile Page

**Mục đích:** Trang công khai hiển thị thông tin seller và sản phẩm.

**Bước thực hiện:**

1. Truy cập `/seller/jerry-games`
2. Verify:
   - Hiển thị shop name "Jerry Games"
   - Thống kê: số sản phẩm, lượt bán, ngày tham gia
   - Danh sách sản phẩm dạng grid
   - Click sản phẩm → chuyển đến detail page

3. Test seller không tồn tại: `/seller/khong-ton-tai`
   - Expected: 404 page

---

### TC-B2: Reviews/Rating

**Mục đích:** Customer có thể xem và viết đánh giá trên source game.

**Bước thực hiện:**

1. **Xem reviews (chưa đăng nhập):**
   - Vào `/source-game/{slug}` bất kỳ
   - Click tab "Đánh giá"
   - Verify: hiển thị "Đăng nhập để viết đánh giá"

2. **Viết review (đã đăng nhập):**
   - Đăng nhập customer → vào source game detail
   - Click tab "Đánh giá"
   - Verify: form đánh giá hiển thị
   - Click sao (1-5) → verify sao sáng lên đúng
   - Nhập tiêu đề + nhận xét → Submit
   - Verify: redirect thành công (review cần admin approve)

3. **Admin approve review:**
   - Admin → `/admin/customers/reviews`
   - Tìm review vừa tạo → Approve
   - Quay lại source game detail → tab Đánh giá
   - Verify: review hiển thị với tên, sao, ngày, nội dung

---

### TC-B3: Wishlist

**Mục đích:** Customer có thể thêm/xóa source game khỏi wishlist.

**Bước thực hiện:**

1. **Chưa đăng nhập:**
   - Vào source game detail
   - Verify: nút "🤍 Yêu thích" hiển thị
   - Click → redirect đến trang login

2. **Thêm wishlist (đã đăng nhập):**
   - Đăng nhập → vào source game detail
   - Click "🤍 Yêu thích"
   - Verify: nút đổi thành "❤️ Đã yêu thích"

3. **Xóa wishlist:**
   - Click "❤️ Đã yêu thích"
   - Verify: nút đổi lại "🤍 Yêu thích"

4. **Kiểm tra trong account:**
   - Vào `/customer/account/wishlist`
   - Verify: sản phẩm vừa thêm hiển thị trong danh sách

---

## Sprint C — SEO, Collections, Versions

### TC-C1: SEO JSON-LD

**Mục đích:** Trang detail có structured data cho search engine.

**Bước thực hiện:**

1. **Source game detail:**
   - Vào `/source-game/{slug}`
   - View page source (Ctrl+U)
   - Tìm `application/ld+json`
   - Verify JSON chứa: `@type: SoftwareSourceCode`, `name`, `offers.price`, `programmingLanguage`

2. **Blog detail:**
   - Vào `/blog/{slug}`
   - View page source
   - Tìm `application/ld+json`
   - Verify JSON chứa: `@type: Article`, `headline`, `datePublished`, `author`

3. **Test bằng Google Rich Results Test:**
   - Vào https://search.google.com/test/rich-results
   - Nhập URL source game detail
   - Verify: không có lỗi, structured data được nhận diện

---

### TC-C2: Related Content

**Mục đích:** Blog hiển thị source game liên quan.

**Bước thực hiện:**

1. Vào `/blog/{slug}` bất kỳ
2. Scroll xuống cuối bài viết (trước sidebar)
3. Verify: section "🎮 Source Game có thể bạn quan tâm" hiển thị
4. Verify: 3 source game với ảnh, tên, giá
5. Click 1 source game → chuyển đến detail page đúng

---

### TC-C3: Collections

**Mục đích:** Customer tạo bộ sưu tập và thêm source game vào.

**Bước thực hiện:**

1. **Tạo collection:**
   - Đăng nhập customer → `/collections`
   - Nhập tên "Unity Games" → Click "Tạo mới"
   - Verify: collection mới hiển thị, 0 sản phẩm

2. **Thêm sản phẩm vào collection:**
   ```bash
   # Test qua form hoặc curl (cần CSRF token + session)
   # Hoặc test bằng tinker:
   docker exec lg-php php /var/www/html/artisan tinker --execute="
   \$c = \App\Models\UserCollection::first();
   \App\Models\CollectionItem::create(['collection_id' => \$c->id, 'product_id' => 50]);
   echo 'Added product 50 to collection ' . \$c->name;
   "
   ```

3. **Xem collection detail:**
   - Vào `/collections` → click collection vừa tạo
   - Verify: sản phẩm hiển thị dạng grid với ảnh, tên, giá

4. **Xóa collection:**
   - Vào `/collections` → click 🗑️ → confirm
   - Verify: collection bị xóa, items cũng bị xóa (cascade)

**Verify DB:**

```bash
docker exec lg-php php /var/www/html/artisan tinker --execute="
echo 'Collections: ' . \App\Models\UserCollection::count() . \"\n\";
echo 'Items: ' . \App\Models\CollectionItem::count();
"
```

---

### TC-C4: Version Control

**Mục đích:** Seller upload phiên bản mới cho source game.

**Bước thực hiện:**

1. **Xem version history:**
   - Đăng nhập seller → `/seller/products`
   - Click sản phẩm → vào `/seller/products/{id}/versions`
   - Verify: trang hiển thị "Chưa có phiên bản nào"

2. **Upload version mới:**
   - Nhập version: `1.0.0`
   - Chọn file (zip/rar, max 100MB)
   - Nhập changelog: `- Initial release\n- Basic gameplay`
   - Click "Upload phiên bản"
   - Verify: redirect về trang versions, version mới hiển thị với badge "Mới nhất"

3. **Upload version thứ 2:**
   - Nhập version: `1.1.0`
   - Upload file + changelog
   - Verify: v1.1.0 có badge "Mới nhất", v1.0.0 không có badge
   - Verify: file size hiển thị đúng (MB)

4. **Duplicate version:**
   - Upload lại version `1.0.0`
   - Expected: validation error (unique constraint)

**Verify DB:**

```bash
docker exec lg-php php /var/www/html/artisan tinker --execute="
\App\Models\SourceGameVersion::all(['product_id','version','file_size','downloads','created_at'])->each(function(\$v){
    echo \"product={\$v->product_id} v{\$v->version} size=\" . number_format(\$v->file_size/1048576,1) . \"MB downloads={\$v->downloads}\n\";
});
"
```

---

## Checklist tổng hợp

| # | Test Case | Mô tả | Kết quả |
|---|-----------|-------|---------|
| A1 | Order → Earning | Earning tự tạo khi order completed | ⬜ |
| A2 | Admin Withdrawal | Duyệt/hoàn thành/từ chối rút tiền | ⬜ |
| B1 | Seller Profile | `/seller/{slug}` hiển thị đúng | ⬜ |
| B2 | Reviews | Xem + viết đánh giá + star rating | ⬜ |
| B3 | Wishlist | Thêm/xóa yêu thích | ⬜ |
| C1 | SEO JSON-LD | Structured data trong page source | ⬜ |
| C2 | Related Content | Source game trên blog detail | ⬜ |
| C3 | Collections | Tạo/xem/xóa bộ sưu tập | ⬜ |
| C4 | Version Control | Upload/xem version history | ⬜ |
