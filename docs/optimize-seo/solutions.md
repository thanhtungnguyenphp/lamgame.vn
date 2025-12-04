# Giải pháp tối ưu SEO Index Errors

## 1. Loại bỏ index.php khỏi URL

### Cấu hình .htaccess (Apache)
```apache
RewriteEngine On
RewriteCond %{THE_REQUEST} /index\.php [NC]
RewriteRule ^index\.php/(.*)$ /$1 [L,R=301,NC]
```

### Hoặc cấu hình Nginx
```nginx
if ($request_uri ~* "^/index\.php") {
    rewrite ^/index\.php/(.*)$ /$1 permanent;
}
```

## 2. Xử lý Pagination

### Thêm vào robots.txt
```
User-agent: *
Disallow: /*?page=
```

### Thêm canonical + rel prev/next trong head
```html
<!-- Trang phân trang -->
<link rel="canonical" href="https://lamgame.vn/blog?tag=unity-3d&page=4">
<link rel="prev" href="https://lamgame.vn/blog?tag=unity-3d&page=3">
<link rel="next" href="https://lamgame.vn/blog?tag=unity-3d&page=5">

<!-- Hoặc canonical về trang 1 -->
<link rel="canonical" href="https://lamgame.vn/blog?tag=unity-3d">
```

### Meta robots cho trang phân trang > 1
```html
<meta name="robots" content="noindex, follow">
```

## 3. Block Auth Pages

### Thêm vào robots.txt
```
User-agent: *
Disallow: /auth/
Disallow: /index.php/auth/
```

### Thêm meta robots trong auth pages
```html
<meta name="robots" content="noindex, nofollow">
```

## 4. Redirect URL cũ .html

### .htaccess
```apache
RewriteRule ^(.*)\.html$ /$1 [R=301,L]
```

## 5. Submit URL Removal trong Google Search Console

Xóa các URL không mong muốn:
- Tất cả URL có `/index.php/`
- Tất cả URL có `?page=` (trừ page=1)
- Tất cả URL `/auth/*`

## 6. Cập nhật Sitemap

Chỉ include:
- URL clean (không có index.php)
- Trang đầu tiên của pagination (page=1 hoặc không có param)
- Loại trừ auth pages

## 7. Internal Linking

Kiểm tra và sửa tất cả internal links:
```bash
# Tìm links có index.php
grep -r "index.php" --include="*.php" --include="*.blade.php"
```

## Thứ tự triển khai

1. ✅ Cấu hình redirect index.php (ngay lập tức)
2. ✅ Thêm robots.txt rules (ngay lập tức)
3. ✅ Thêm meta robots cho auth pages (ngay lập tức)
4. ✅ Cấu hình canonical cho pagination (trong 1 tuần)
5. ✅ Submit URL removal GSC (trong 1 tuần)
6. ✅ Cập nhật sitemap (trong 1 tuần)
7. ✅ Fix internal links (trong 2 tuần)
