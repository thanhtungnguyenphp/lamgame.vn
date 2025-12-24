# ✅ SEO Quick Checklist - lamgame.vn

## 🔴 URGENT - Làm ngay (15 phút)

### 1. Submit Sitemap
```
1. Vào: https://search.google.com/search-console
2. Chọn property: lamgame.vn
3. Sitemaps → Add sitemap
4. URL: https://lamgame.vn/sitemap.xml
5. Click Submit
```

### 2. Setup Auto Sitemap Update
```bash
crontab -e
# Add:
0 2 * * * cd /data/www/lamgame.vn && docker exec lg-php php artisan sitemap:generate
```

### 3. Add Canonical URL
Edit: `resources/views/layouts/master.blade.php`
```blade
<link rel="canonical" href="{{ url()->current() }}">
```

## 🟡 HIGH PRIORITY - Tuần này

- [ ] Add alt text cho tất cả images
- [ ] Check H1 tags (1 H1 per page)
- [ ] Add breadcrumb schema
- [ ] Enable image lazy loading

## 🟢 MEDIUM - Tuần tới

- [ ] Setup CloudFlare CDN
- [ ] Optimize images (WebP)
- [ ] Internal linking audit
- [ ] Add FAQ schema

## 📊 Monitoring Tools

**Daily:**
- Google Search Console → Coverage report

**Weekly:**
- PageSpeed Insights: https://pagespeed.web.dev/
- Check indexed pages count

**Monthly:**
- Full SEO audit
- Competition analysis

---

**Current Status:**
✅ Sitemap: 90 URLs
✅ Structured Data: JobPosting, Article
⚠️ Needs: Submit to GSC, Canonical URLs
