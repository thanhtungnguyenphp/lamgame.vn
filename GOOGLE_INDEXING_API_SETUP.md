# Hướng dẫn Setup Google Indexing API cho lamgame.vn

## 📋 Tổng quan
Google Indexing API giúp push URLs mới/cập nhật lên Google để index **nhanh hơn** (thay vì chờ Google crawl tự nhiên có thể mất vài ngày).

## 🎯 Lợi ích
- ⚡ Index nhanh trong vài phút thay vì vài ngày
- 🎯 Ưu tiên URLs quan trọng (jobs mới, blogs mới)
- 📊 Quota free: 200 URLs/ngày

---

## 🚀 Các bước Setup

### Bước 1: Tạo Google Cloud Project

1. Truy cập: https://console.cloud.google.com/
2. Click "Select a project" → "New Project"
3. Đặt tên: `lamgame-indexing` (hoặc tên khác)
4. Click **Create**

### Bước 2: Enable Indexing API

1. Vào **APIs & Services** → **Library**
2. Tìm kiếm: `Indexing API`
3. Click vào **Indexing API**
4. Click **Enable**

### Bước 3: Tạo Service Account

1. Vào **APIs & Services** → **Credentials**
2. Click **Create Credentials** → **Service Account**
3. Điền thông tin:
   - Service account name: `lamgame-indexing-bot`
   - Service account ID: (tự động generate)
   - Click **Create and Continue**
4. Grant role: **Owner** (hoặc Editor)
5. Click **Continue** → **Done**

### Bước 4: Download JSON Key

1. Trong danh sách Service Accounts, click vào account vừa tạo
2. Tab **Keys** → **Add Key** → **Create new key**
3. Chọn **JSON** → **Create**
4. File JSON sẽ được download về máy (tên dạng: `lamgame-indexing-xxx.json`)

### Bước 5: Cấp quyền trong Google Search Console

1. Mở file JSON vừa download, tìm field `client_email`
   - Ví dụ: `lamgame-indexing-bot@lamgame-indexing.iam.gserviceaccount.com`
2. Truy cập: https://search.google.com/search-console
3. Chọn property `lamgame.vn`
4. Vào **Settings** → **Users and permissions**
5. Click **Add user**
6. Nhập email từ bước 1: `lamgame-indexing-bot@...`
7. Chọn permission: **Owner**
8. Click **Add**

### Bước 6: Upload JSON Key lên Server

```bash
# SSH vào server
ssh root@your-server

# Di chuyển đến thư mục dự án
cd /data/www/lamgame.vn

# Upload file JSON (có thể dùng scp hoặc copy-paste)
# Cách 1: Copy từ máy local
scp lamgame-indexing-xxx.json root@your-server:/data/www/lamgame.vn/storage/app/google-service-account.json

# Cách 2: Tạo file và paste nội dung
nano storage/app/google-service-account.json
# Paste nội dung file JSON → Ctrl+X → Y → Enter

# Set permissions
chmod 600 storage/app/google-service-account.json
```

### Bước 7: Test

```bash
# Test push 5 URLs
docker exec lg-php php artisan google:push-index --type=all --limit=5

# Kết quả mong đợi:
# 🚀 Starting Google Indexing API push...
# ✅ Access token obtained
# 📋 Pushing job posts...
# ✅ https://lamgame.vn/viec-lam/...
# 📊 Jobs: X success, 0 failed
```

---

## 📝 Cách sử dụng

### Push URLs mới

```bash
# Push tất cả (jobs + blogs), giới hạn 10 URLs
docker exec lg-php php artisan google:push-index --type=all --limit=10

# Push chỉ jobs
docker exec lg-php php artisan google:push-index --type=jobs --limit=20

# Push chỉ blogs
docker exec lg-php php artisan google:push-index --type=blogs --limit=15
```

### Tự động hóa (Cron)

Đã được setup sẵn trong `app/Console/Kernel.php`:
- Chạy mỗi 6 giờ
- Push 10 URLs mới nhất

```php
// Đã có trong code
$schedule->command('google:push-index --type=all --limit=10')
    ->everySixHours();
```

---

## ⚠️ Lưu ý quan trọng

### Rate Limits
- **200 requests/minute** (tool tự động delay 0.3s/request)
- **200 URLs/day** (free quota)

### Nên Push
✅ Jobs mới đăng
✅ Blogs mới publish
✅ Pages có update nội dung lớn

### Không nên Push
❌ Pagination pages (`?page=2`)
❌ Filter/search results
❌ Auth pages
❌ Duplicate content

---

## 🔍 Troubleshooting

### Lỗi: "Permission denied"
→ Check lại permission trong Google Search Console (Bước 5)

### Lỗi: "Invalid credentials"
→ Check file JSON đã copy đúng chưa

### Lỗi: "API not enabled"
→ Enable Indexing API trong Google Cloud Console (Bước 2)

### Lỗi: "Quota exceeded"
→ Đã vượt 200 URLs/day, đợi 24h

---

## 📊 Monitoring

Kiểm tra kết quả trong Google Search Console:
1. Vào https://search.google.com/search-console
2. Chọn property `lamgame.vn`
3. **Coverage** → Xem số lượng indexed pages tăng

---

## 🎉 Hoàn tất!

Sau khi setup xong, Google Indexing API sẽ:
- Tự động chạy mỗi 6h (qua cron)
- Push 10 URLs mới nhất lên Google
- Giúp index nhanh hơn 10-100x so với crawl tự nhiên

**Next step:** Submit sitemap lên Google Search Console nếu chưa làm!
