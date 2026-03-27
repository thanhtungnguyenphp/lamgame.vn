# Source Game Marketplace & Seller System — Trạng thái & Roadmap

**Cập nhật:** 2026-03-27
**Tài liệu trước:** 2025-12-23 (Phase 2 completed)

---

## 1. Trạng thái hiện tại

### ✅ Đã hoàn thành

| Module | Chi tiết | Routes |
|--------|----------|--------|
| **Source Game Listing** | Browse, search, sort, phân trang, filter category | `/source-game` |
| **Source Game Detail** | Images, downloadable links, attributes, related products | `/source-game/{slug}` |
| **Checkout/Download** | Cart → Payment → Download (Bagisto core) | `/checkout/*` |
| **Seller Registration** | Form đăng ký (cá nhân/doanh nghiệp), upload logo/banner | `seller/register` |
| **Admin Approval** | Duyệt/từ chối/suspend seller, email notification | `admin/sellers/*` (8 routes) |
| **Seller Dashboard** | Stats cards, revenue chart (Chart.js), recent orders, quick actions | `seller/dashboard` |
| **Product Upload** | CRUD sản phẩm downloadable, multi-file upload, validation | `seller/products/*` (7 routes) |
| **Revenue Sharing** | Commission 30%, earnings tracking | `seller/earnings` |
| **Withdrawal System** | Request rút tiền (min 100k VND), bank info | `seller/withdrawals/*` (3 routes) |
| **Middleware** | `CheckSeller` — bảo vệ routes, kiểm tra status | Registered |
| **Admin Menu** | Menu Sellers trong admin panel | Config-based |
| **Layout** | Custom account layout tích hợp seller navigation | Component |

**Database:** 3 bảng custom (`source_game_sellers`, `source_game_earnings`, `source_game_withdrawals`) + `seller_id` column trên `products`

**Thống kê:** 26 routes, 1 seller đăng ký, 1 product có seller_id

### 🔴 Chưa hoàn thành (từ Phase 2)

| # | Vấn đề | Mức độ | Ghi chú |
|---|--------|--------|---------|
| 1 | Order completion hook → tạo earning record | ✅ Done | Listener `CreateSellerEarningOnOrderComplete` on `sales.order.update-status.after` |
| 2 | Admin withdrawal processing UI | ✅ Done | `AdminWithdrawalController` + view + 4 routes + admin menu |
| 3 | Email SMTP chưa config production | ✅ Done | smtp2go đã config sẵn |
| 4 | Virus scanning cho uploaded files | 🟢 Thấp | ClamAV chưa tích hợp |

### 📋 Chưa bắt đầu (từ kế hoạch Phase 3-4)

| # | Feature | Phase | Mô tả |
|---|---------|-------|-------|
| 1 | Wishlist | 3 | Yêu thích source game |
| 2 | Collections | 3 | Bộ sưu tập cá nhân |
| 3 | Reviews/Rating | 3 | Đánh giá + rating 1-5 sao |
| 4 | Seller Profile Page | 3 | Trang `/seller/{slug}` công khai |
| 5 | Version Control | 3 | Upload version mới, changelog |
| 6 | License Management | 3 | Personal/Commercial/Open Source |
| 7 | Enhanced Preview | 3 | WebGL embed, video, code viewer |
| 8 | Analytics & Insights | 3 | Product + seller analytics |
| 9 | Performance Optimization | 4 | Caching, DB tuning, Lighthouse 95+ |
| 10 | SEO & Marketing | 4 | Schema.org, sitemap, email campaigns |

---

## 2. Cấu trúc tài liệu

```
docs/source_game/
├── README.md              ← Tổng quan dự án (index)
├── STATUS.md              ← File này — trạng thái & roadmap
├── 01_TONG_QUAN.md        ← Phân tích chức năng tổng quan
├── 02_KY_THUAT.md         ← Database schema, API spec, workflows
├── 03_KE_HOACH_PHAT_TRIEN.md ← Kế hoạch 4 phases chi tiết
├── 04_TOI_UU_HOA.md       ← Chiến lược tối ưu hóa
├── TODO.md                ← Danh sách feature cần làm (detail page)
├── QUICK_REFERENCE.md     ← Tham chiếu nhanh cho developer
├── layout/                ← Mockup giao diện
└── _archive/              ← Báo cáo hoàn thành cũ (9 files, chỉ tham khảo)
```

---

## 3. Roadmap tiếp theo — Đề xuất ưu tiên

### ✅ Sprint A — Fix critical gaps (hoàn thành 2026-03-27)

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| A1 | **Order → Earning hook** | Listener `CreateSellerEarningOnOrderComplete` — tạo earning (70/30) khi order completed | ✅ Done |
| A2 | **Admin withdrawal UI** | `AdminWithdrawalController` + view + 4 routes + admin menu | ✅ Done |
| A3 | **Email config** | SMTP smtp2go đã config sẵn | ✅ Done |
| A4 | **Test end-to-end flow** | Seller, product, listeners, routes — all verified | ✅ Done |

### ✅ Sprint B — Seller Profile + Reviews (hoàn thành 2026-03-27)

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| B1 | **Seller Profile Page** | `/seller/{slug}` — shop info, stats, product grid | ✅ Done |
| B2 | **Reviews/Rating** | Reviews list + form đánh giá + star rating UI | ✅ Done |
| B3 | **Wishlist** | Nút yêu thích toggle trên source game detail | ✅ Done |

### ✅ Sprint C — Growth features (hoàn thành 2026-03-27)

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| C1 | **SEO optimization** | Schema.org JSON-LD cho source game + blog, OG image | ✅ Done |
| C2 | **Related content linking** | Source games trên blog detail, seller link trên source game | ✅ Done |
| C3 | **Collections** | User collections CRUD + add/remove items, 2 bảng DB, 6 routes | ✅ Done |
| C4 | **Version control** | Upload version mới, changelog, version history, 1 bảng DB | ✅ Done |

---

## 4. Cấu trúc code hiện tại

```
app/Http/Controllers/
├── SellerController.php           # Registration, dashboard, orders, analytics
├── SellerProductController.php    # Product CRUD (downloadable)
├── SellerEarningController.php    # Earnings + Withdrawals
├── Admin/
│   ├── AdminSellerController.php  # Admin approval system
│   ├── AdminProductController.php # Admin product management
│   └── AdminWithdrawalController.php # Admin withdrawal processing

app/Listeners/
├── SendSellerOrderNotification.php      # Email seller khi có order mới
└── CreateSellerEarningOnOrderComplete.php # Tạo earning khi order completed

app/Models/
├── SourceGameSeller.php           # Seller model
├── SourceGameEarning.php          # Earning model
└── SourceGameWithdrawal.php       # Withdrawal model

app/Http/Middleware/
└── CheckSeller.php                # Seller auth middleware

resources/views/seller/            # Seller views (register, dashboard, products, etc.)
resources/views/admin/sellers/     # Admin seller views
```

---

## 5. Database Schema tóm tắt

```
source_game_sellers
├── customer_id (FK → customers)
├── shop_name, shop_slug, shop_description
├── logo, banner
├── contact_email, contact_phone, website
├── business_type (individual/company), tax_id
├── bank_name, bank_account, bank_holder
├── status (pending/active/rejected/suspended/banned)
├── total_products, total_sales, total_earnings, rating_average
└── approved_at, approved_by

source_game_earnings
├── seller_id, order_id, order_item_id, product_id
├── total_amount, platform_fee (30%), seller_amount (70%)
├── status (pending/completed/refunded)
└── completed_at

source_game_withdrawals
├── seller_id
├── amount, bank_name, bank_account, bank_holder
├── status (pending/processing/completed/rejected)
├── admin_note, processed_at, processed_by
└── transaction_reference
```
