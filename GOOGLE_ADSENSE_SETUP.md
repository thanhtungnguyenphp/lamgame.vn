# Google AdSense Setup Documentation

## Meta Tag Implementation

Google AdSense verification meta tag đã được thêm vào toàn bộ site.

### Meta Tag
```html
<meta name="google-adsense-account" content="ca-pub-5812352607411986">
```

### Location
- **File**: `resources/views/layouts/master.blade.php`
- **Line**: ~26 (sau Open Graph tags, trước @stack('meta'))

### Cấu trúc HTML Head
```html
<!-- Open Graph Meta Tags -->
<meta property="og:title" content="...">
<meta property="og:description" content="...">
<meta property="og:image" content="...">
<meta property="og:url" content="...">
<meta property="og:type" content="website">

<!-- Google AdSense -->
<meta name="google-adsense-account" content="ca-pub-5812352607411986">

<!-- Canonical URL -->
<link rel="canonical" href="{{ url()->current() }}">

@stack('meta')
```

## Changes Made

### 1. Master Layout (`resources/views/layouts/master.blade.php`)
- ✅ Thêm Google AdSense meta tag
- ✅ Thêm Canonical URL tag (áp dụng cho tất cả pages)

### 2. Homepage View (`resources/views/home/index.blade.php`)
- ✅ Xóa duplicate canonical URL tag (đã có trong master layout)

### 3. Backups Created
- `resources/views/layouts/master.blade.php.backup.adsense`
- `resources/views/home/index.blade.php.backup.adsense`

## Verification

### Check Meta Tag on Live Site
```bash
# Homepage
curl -s https://lamgame.vn | grep "google-adsense-account"

# Job pages
curl -s https://lamgame.vn/viec-lam/s-38 | grep "google-adsense-account"

# Blog pages
curl -s https://lamgame.vn/blog/huong-dan-unity-2023-tinh-nang-moi | grep "google-adsense-account"
```

### Check for Duplicate Canonical Tags
```bash
# Should return 1 (not 2)
curl -s https://lamgame.vn | grep -c 'rel="canonical"'
```

## Applied To

✅ **All pages** (via master layout):
- Homepage
- Job listing page
- Job detail pages
- Blog listing page
- Blog detail pages
- Forum pages
- All static pages

## SEO Benefits

1. **Google AdSense**: Verified ownership for ad placement
2. **Canonical URL**: Prevents duplicate content issues
3. **Consistent**: Applied across all pages automatically

## Next Steps for AdSense

1. Verify ownership trong Google AdSense dashboard
2. Setup ad units sau khi được approve
3. Add ad code vào appropriate locations:
   - Header/footer
   - Sidebar
   - Between content sections
   - Job/blog detail pages

## Notes

- Meta tag nằm trong `<head>` section
- Được load trên mọi page qua master layout
- Không cần config thêm trong .env
- Canonical URL tự động match với current URL
- Không ảnh hưởng đến performance

## Cache Management

Sau khi thay đổi view:
```bash
docker exec lg-php php artisan view:clear
docker exec lg-php php artisan config:clear
```

## Rollback (nếu cần)

```bash
# Restore master layout
cp resources/views/layouts/master.blade.php.backup.adsense \
   resources/views/layouts/master.blade.php

# Restore homepage
cp resources/views/home/index.blade.php.backup.adsense \
   resources/views/home/index.blade.php

# Clear cache
docker exec lg-php php artisan view:clear
```
