# LamGame — Banner API Documentation

> Cập nhật: 2026-05-07 | Package: `packages/LamGame/Banner`
> Base URL Production: `https://lamgame.vn/api`

---

## Tổng quan

Hệ thống Banner gồm 2 nhóm API:

| Nhóm | Prefix | Auth | Mục đích |
|------|--------|------|----------|
| Public Display | `/api/banners` | Không cần | Hiển thị banner trên frontend/app |
| Admin Management | `/api/admin/banners` | `auth:sanctum` | CRUD quản lý banner |
| Dynamic Content | `/api/banner` | Không cần | Dữ liệu động cho banner widgets |

**Tổng: 19 endpoints**

---

## Response Format

```json
{
  "success": true,
  "data": { ... },
  "meta": { ... }
}
```

**Error:**
```json
{
  "success": false,
  "message": "Error description"
}
```

---

# PHẦN 1: PUBLIC DISPLAY API

**Prefix:** `/api/banners`  
**Auth:** Không cần  
**Rate Limit:** 60 req/min  
**Cache:** 5 phút (Cache-Control header)

---

## 1.1 Lấy danh sách banner

### GET /api/banners

Lấy banner theo bộ lọc.

**Query params:**
| Param | Type | Mô tả |
|-------|------|--------|
| position | string | Vị trí banner (homepage_hero, sidebar_top, ...) |
| device_type | string | all, desktop, tablet, mobile |
| channel_id | int | Channel ID |
| locale | string | Locale (vi, en) |
| limit | int | Giới hạn kết quả (max 50) |

**Request:**
```http
GET /api/banners?position=homepage_hero&device_type=mobile&locale=vi&limit=5
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Homepage Hero Banner",
      "type": "image",
      "position": "homepage_hero",
      "device_type": "all",
      "title": "Welcome to LamGame",
      "content": "<p>Learn game development</p>",
      "image": "https://lamgame.vn/storage/banners/hero.jpg",
      "responsive_images": {
        "mobile": "https://lamgame.vn/storage/banners/hero.jpg?w=480",
        "tablet": "https://lamgame.vn/storage/banners/hero.jpg?w=768",
        "desktop": "https://lamgame.vn/storage/banners/hero.jpg?w=1200",
        "large": "https://lamgame.vn/storage/banners/hero.jpg?w=1920"
      },
      "image_alt": "Homepage hero banner",
      "link": "https://lamgame.vn/courses",
      "target": "_self",
      "css_classes": "hero-banner fade-in",
      "html_attributes": "data-analytics=\"banner-hero\"",
      "settings": {"animation": "slideUp"},
      "sort_order": 0,
      "start_date": "2025-01-01T00:00:00Z",
      "end_date": null,
      "is_active": true,
      "channel": { "id": 1, "name": "Default", "code": "default" }
    }
  ],
  "meta": {
    "count": 1,
    "filters": { "position": "homepage_hero", "device_type": "mobile" }
  }
}
```

**Lưu ý:** Tự động track impressions khi gọi. Gửi header `X-Track-Impressions: false` để tắt tracking.

---

## 1.2 Lấy banner theo vị trí

### GET /api/banners/position/{position}

**Params:**
| Param | Type | Mô tả |
|-------|------|--------|
| position | string (URL) | Vị trí banner |
| device_type | string (query) | all, desktop, tablet, mobile |
| channel_id | int (query) | Channel ID |
| locale | string (query) | Locale |
| limit | int (query) | Giới hạn |

**Request:**
```http
GET /api/banners/position/sidebar_top?device_type=desktop&limit=3
```

---

## 1.3 Track click

### POST /api/banners/{id}/track-click

Ghi nhận click vào banner.

**Request:**
```http
POST /api/banners/1/track-click
```

**Response:**
```json
{ "success": true, "message": "Click tracked successfully" }
```

---

## 1.4 Track impression

### POST /api/banners/{id}/track-impression

Ghi nhận hiển thị banner (manual tracking).

---

## 1.5 Danh sách vị trí

### GET /api/banners/positions

**Response:**
```json
{
  "success": true,
  "data": [
    { "value": "homepage_hero", "label": "Homepage Hero" },
    { "value": "homepage_secondary", "label": "Homepage Secondary" },
    { "value": "sidebar_top", "label": "Sidebar Top" },
    { "value": "sidebar_bottom", "label": "Sidebar Bottom" },
    { "value": "header", "label": "Header" },
    { "value": "footer", "label": "Footer" },
    { "value": "product_detail", "label": "Product Detail" },
    { "value": "category_page", "label": "Category Page" },
    { "value": "checkout", "label": "Checkout" },
    { "value": "custom", "label": "Custom Position" }
  ]
}
```

Cache 1 giờ.

---

## 1.6 Danh sách device types

### GET /api/banners/device-types

**Response:**
```json
{
  "success": true,
  "data": [
    { "value": "all", "label": "All Devices" },
    { "value": "desktop", "label": "Desktop" },
    { "value": "tablet", "label": "Tablet" },
    { "value": "mobile", "label": "Mobile" }
  ]
}
```

---

## 1.7 Xóa banner

### DELETE /api/banners/{id}

---

# PHẦN 2: ADMIN MANAGEMENT API

**Prefix:** `/api/admin/banners`  
**Auth:** `auth:sanctum` (Bearer token)  
**Rate Limit:** Default

---

## 2.1 Danh sách banner (Admin)

### GET /api/admin/banners

**Query params:**
| Param | Type | Mô tả |
|-------|------|--------|
| position | string | Lọc theo vị trí |
| status | bool | true/false |
| device_type | string | all, desktop, tablet, mobile |
| type | string | image, html, video |
| channel_id | int | Channel ID |
| search | string | Tìm theo name/title |
| sort_by | string | sort_order (default), name, created_at |
| sort_dir | string | asc (default), desc |
| per_page | int | Default 15 |

**Response:**
```json
{
  "success": true,
  "data": [ ... ],
  "meta": { "current_page": 1, "last_page": 3, "per_page": 15, "total": 42 }
}
```

---

## 2.2 Chi tiết banner

### GET /api/admin/banners/{id}

---

## 2.3 Tạo banner

### POST /api/admin/banners

**Content-Type:** `multipart/form-data` (nếu có upload image)

**Body:**
| Field | Type | Required | Mô tả |
|-------|------|----------|--------|
| name | string | ✅ | Tên banner (unique) |
| type | string | ✅ | image, html, video |
| position | string | ✅ | Vị trí hiển thị |
| device_type | string | ✅ | all, desktop, tablet, mobile |
| channel_id | int | | Channel ID |
| locale | string | | Locale (vi, en) |
| title | string | | Tiêu đề hiển thị |
| content | string | | Nội dung HTML |
| image | file | | Hình ảnh (jpg,png,gif,webp,svg, max 5MB) |
| image_alt | string | | Alt text cho hình |
| link | url | | URL khi click |
| target | string | | _self, _blank |
| css_classes | string | | CSS classes |
| attributes | string | | HTML attributes |
| settings | string | | JSON settings |
| start_date | date | | Ngày bắt đầu hiển thị |
| end_date | date | | Ngày kết thúc (≥ start_date) |
| sort_order | int | | Thứ tự (default 0) |
| status | bool | | Active/inactive (default true) |

**Request:**
```http
POST /api/admin/banners
Authorization: Bearer {token}
Content-Type: multipart/form-data

name: Summer Sale Banner
type: image
position: homepage_hero
device_type: all
title: Summer Sale 50%
link: https://lamgame.vn/sale
target: _self
image: (file) banner.jpg
start_date: 2026-06-01
end_date: 2026-06-30
status: 1
```

**Response:** 201
```json
{
  "success": true,
  "message": "Banner created successfully",
  "data": { ... }
}
```

---

## 2.4 Cập nhật banner

### POST /api/admin/banners/{id}

Dùng POST (thay vì PUT) vì hỗ trợ file upload. Tất cả fields optional.

---

## 2.5 Xóa banner

### DELETE /api/admin/banners/{id}

Xóa banner + file image trên storage.

---

## 2.6 Xóa hàng loạt

### POST /api/admin/banners/mass-destroy

```json
{ "ids": [1, 2, 3] }
```

---

## 2.7 Cập nhật trạng thái hàng loạt

### POST /api/admin/banners/mass-update

```json
{ "ids": [1, 2, 3], "status": false }
```

---

## 2.8 Sắp xếp thứ tự

### PUT /api/admin/banners/update-order

```json
{
  "orders": [
    { "id": 3, "sort_order": 0 },
    { "id": 1, "sort_order": 1 },
    { "id": 2, "sort_order": 2 }
  ]
}
```

---

## 2.9 Analytics

### GET /api/admin/banners/analytics

**Query params:** position, device_type, channel_id, date_from, date_to

**Response:** Thống kê impressions, clicks, CTR theo banner.

---

## 2.10 Options

### GET /api/admin/banners/options

Trả về tất cả options cho form tạo/sửa banner.

```json
{
  "success": true,
  "data": {
    "positions": [{ "value": "homepage_hero", "label": "Homepage Hero" }, ...],
    "device_types": [{ "value": "all", "label": "All Devices" }, ...],
    "banner_types": [{ "value": "image", "label": "Image Banner" }, ...]
  }
}
```

---

# PHẦN 3: DYNAMIC CONTENT API

**Prefix:** `/api/banner`  
**Auth:** Không cần  
**Rate Limit:** 60 req/min (riêng `/all` là 30 req/min)  
**Cache:** 5 phút  
**Controller:** `app/Http/Controllers/Api/BannerController.php`

Cung cấp dữ liệu động cho banner widgets trên trang chủ.

---

## 3.1 Jobs Data

### GET /api/banner/jobs

```json
{
  "success": true,
  "data": {
    "count": 55,
    "companies": ["VNG", "Gameloft", "Nexon", "Amanotes", "VTC"],
    "latest_salary_range": "20-45tr VNĐ",
    "new_this_week": 18,
    "updated_at": "2026-05-07T12:00:00.000Z"
  }
}
```

---

## 3.2 Hot Topics

### GET /api/banner/topics

```json
{
  "success": true,
  "data": {
    "title": "Unity vs Unreal cho game mobile?",
    "author": "GameDev42",
    "stats": { "comments": 85, "views": 450, "likes": 60 },
    "url": "/forum/posts/123",
    "updated_at": "2026-05-07T12:00:00.000Z"
  }
}
```

---

## 3.3 Latest Blog

### GET /api/banner/blogs

```json
{
  "success": true,
  "data": {
    "title": "Tối ưu hóa performance Unity cho game 3D",
    "author": "LamGame Team",
    "excerpt": "Bài viết chia sẻ kinh nghiệm tối ưu...",
    "stats": { "views": 280, "shares": 45, "reading_time": "8 phút đọc" },
    "url": "/blog/toi-uu-performance-unity",
    "published_at": "2026-05-05T10:00:00.000Z",
    "updated_at": "2026-05-07T12:00:00.000Z"
  }
}
```

---

## 3.4 Source Games

### GET /api/banner/sources

```json
{
  "success": true,
  "data": {
    "project": "Roguelike Unity Kit",
    "idea": "VR adventure Việt Nam folklore",
    "stats": { "downloads": 1200, "stars": 150, "contributors": 8 },
    "updated_at": "2026-05-07T12:00:00.000Z"
  }
}
```

---

## 3.5 All Data (Recommended)

### GET /api/banner/all

Gộp tất cả 4 loại data trong 1 request.

```json
{
  "success": true,
  "data": {
    "jobs": { ... },
    "topics": { ... },
    "blogs": { ... },
    "sources": { ... },
    "updated_at": "2026-05-07T12:00:00.000Z"
  }
}
```

---

# TÍCH HỢP

## Mobile App — Hiển thị banner

```javascript
// 1. Lấy banner theo vị trí + device
const res = await fetch('https://lamgame.vn/api/banners/position/homepage_hero?device_type=mobile');
const { data } = await res.json();

// 2. Hiển thị
data.forEach(banner => {
  // Dùng responsive_images theo screen size
  const imgUrl = banner.responsive_images?.mobile || banner.image;
  // Render banner...
});

// 3. Track click khi user tap
await fetch(`https://lamgame.vn/api/banners/${bannerId}/track-click`, { method: 'POST' });
```

## Mobile App — Banner widgets động

```javascript
// Lấy tất cả data cho widgets
const res = await fetch('https://lamgame.vn/api/banner/all');
const { data } = await res.json();

// Widget: Việc làm
console.log(`${data.jobs.count} việc làm game`);

// Widget: Hot topic
console.log(data.topics.title);

// Widget: Blog mới
console.log(data.blogs.title);
```

## Admin — Quản lý banner

```javascript
// Tạo banner mới
const formData = new FormData();
formData.append('name', 'New Banner');
formData.append('type', 'image');
formData.append('position', 'homepage_hero');
formData.append('device_type', 'all');
formData.append('image', fileInput.files[0]);
formData.append('link', 'https://lamgame.vn/sale');

await fetch('https://lamgame.vn/api/admin/banners', {
  method: 'POST',
  headers: { 'Authorization': 'Bearer ' + token },
  body: formData
});
```

---

# CẤU HÌNH

File: `config/banner.php`

| Key | Default | Mô tả |
|-----|---------|--------|
| cache.ttl | 3600 | Cache TTL (seconds) |
| cache.enabled | true | Bật/tắt cache |
| analytics.track_impressions | true | Track lượt hiển thị |
| analytics.track_clicks | true | Track lượt click |
| images.max_size | 5120 | Max file size (KB) |
| images.allowed_types | jpg,png,gif,webp | Định dạng cho phép |
| api.rate_limit | 60,1 | Rate limit |

---

# POSITIONS REFERENCE

| Value | Label | Mô tả |
|-------|-------|--------|
| homepage_hero | Homepage Hero | Banner lớn trang chủ |
| homepage_secondary | Homepage Secondary | Banner phụ trang chủ |
| sidebar_top | Sidebar Top | Sidebar trên |
| sidebar_bottom | Sidebar Bottom | Sidebar dưới |
| header | Header | Header site |
| footer | Footer | Footer site |
| product_detail | Product Detail | Trang chi tiết sản phẩm |
| category_page | Category Page | Trang danh mục |
| checkout | Checkout | Trang thanh toán |
| custom | Custom Position | Vị trí tùy chỉnh |

---

# FILES REFERENCE

| File | Path |
|------|------|
| Package | `packages/LamGame/Banner/` |
| Public API Controller | `packages/LamGame/Banner/src/Http/Controllers/Api/BannerController.php` |
| Management API Controller | `packages/LamGame/Banner/src/Http/Controllers/Api/BannerManagementController.php` |
| Admin Controller | `packages/LamGame/Banner/src/Http/Controllers/Admin/BannerController.php` |
| Repository | `packages/LamGame/Banner/src/Repositories/BannerRepository.php` |
| Routes (Public API) | `packages/LamGame/Banner/src/Routes/api.php` |
| Routes (Management API) | `packages/LamGame/Banner/src/Routes/api-management.php` |
| Routes (Admin Web) | `packages/LamGame/Banner/src/Routes/admin.php` |
| Config | `config/banner.php` |
| Dynamic Content Controller | `app/Http/Controllers/Api/BannerController.php` |
| Dynamic Content Routes | `routes/api.php` (dòng 85-105) |
