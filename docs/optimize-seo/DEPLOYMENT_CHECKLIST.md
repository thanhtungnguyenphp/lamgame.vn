# SEO Optimization Deployment Checklist

## ✅ Đã hoàn thành (Local)

- [x] Cập nhật `.htaccess` với redirect index.php và .html
- [x] Cập nhật `robots.txt` block auth và pagination
- [x] Tạo middleware `SeoMetaRobots` cho noindex
- [x] Đăng ký middleware trong `bootstrap/app.php`
- [x] Tạo danh sách URLs cần xóa (106 index.php, 3 auth)
- [x] Tạo script test redirects

## 🔄 Cần làm tiếp (Production)

### Bước 1: Deploy lên Production (Ngay lập tức)
```bash
# Commit changes
git add public/.htaccess public/robots.txt app/Http/Middleware/SeoMetaRobots.php bootstrap/app.php
git commit -m "SEO: Add redirects for index.php, block auth/pagination in robots.txt"
git push origin main

# Deploy
# (Tùy theo quy trình deploy của bạn)
```

### Bước 2: Test trên Production (Sau deploy 5 phút)
```bash
# Chạy script test
./docs/optimize-seo/test-redirects.sh

# Hoặc test thủ công:
# 1. Mở https://lamgame.vn/index.php/blog → Phải redirect về /blog
# 2. Kiểm tra view-source:https://lamgame.vn/auth/login → Có <meta name="robots" content="noindex, nofollow">
# 3. Kiểm tra view-source:https://lamgame.vn/blog?page=2 → Có <meta name="robots" content="noindex, follow">
```

### Bước 3: Submit URL Removal (Trong 1 ngày)
Làm theo hướng dẫn: `docs/optimize-seo/google-search-console-removal.md`

1. Xóa prefix: `https://lamgame.vn/index.php/`
2. Xóa prefix: `https://lamgame.vn/auth/`
3. Xóa prefix: `https://lamgame.vn/index.php/auth/`

### Bước 4: Update Sitemap (Trong 3 ngày)
- [ ] Loại bỏ tất cả URLs có `index.php`
- [ ] Loại bỏ tất cả URLs có `?page=` (trừ page=1)
- [ ] Loại bỏ tất cả auth URLs
- [ ] Submit sitemap mới lên GSC

### Bước 5: Fix Internal Links (Trong 1 tuần)
```bash
# Tìm internal links có index.php
grep -r "index.php" resources/views --include="*.blade.php"
grep -r "index.php" packages/*/src/Resources/views --include="*.blade.php"

# Sửa tất cả thành clean URLs
```

### Bước 6: Monitor (Hàng ngày trong 2 tuần)
- [ ] Kiểm tra Coverage Report trong GSC
- [ ] Kiểm tra 404 errors
- [ ] Kiểm tra traffic không giảm
- [ ] Kiểm tra số lượng indexed pages giảm dần

## 📊 Metrics để theo dõi

| Metric | Trước | Sau (2 tuần) | Sau (4 tuần) |
|--------|-------|--------------|--------------|
| Indexed pages | ~127 | ~50 | ~30 |
| Index.php URLs | 106 | 0 | 0 |
| Auth URLs | 3 | 0 | 0 |
| Organic traffic | Baseline | +/- 5% | +10-20% |

## ⚠️ Rollback Plan

Nếu có vấn đề:
```bash
# Revert .htaccess
git revert <commit-hash>
git push origin main

# Hoặc tạm thời disable redirect
# Comment out dòng redirect trong .htaccess
```

## 📞 Support

Nếu cần hỗ trợ, check:
- Google Search Console → Coverage Report
- Server logs: `/var/log/apache2/error.log` hoặc `/var/log/nginx/error.log`
- Laravel logs: `storage/logs/laravel.log`
