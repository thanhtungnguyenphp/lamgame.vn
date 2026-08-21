# LamGame.vn Phase 1 SEO Cleanup — Quick Reference

## 🎯 North Star
```
LamGame.vn — Hệ sinh thái dành cho Game Developer Việt Nam
Learn → Build → Connect → Work → Ship
```

## ✅ COMPLETED — Sprint 1 (14/08/2026)

### Web Routes Disabled (410 Gone)
- `/xo-so/*` — All lottery web pages
- `/the-thao/*` — All sport web pages
- `/world-cup-2026` — World Cup landing
- `/lottolive` — Lottery app landing

### Navigation Cleaned
- Footer: Removed Thể thao, Xổ số, World Cup → replaced with Unity, Game Design, Chơi Game
- Header v2: Removed Thể thao, Xổ số → replaced with Game Industry, AI Game Dev
- Nav redesign: Same changes
- Homepage: Removed all lottery/sport links → replaced with Game Dev categories
- Home v2: Replaced Xổ số widget with AI Tools widget

### Scheduler Jobs Disabled
- `lottery:scrape --region=*` — commented out
- `ScrapeVietlotLottery` — commented out

### Sitemap Updated
- Excluded `lottolive`, `ung-dung-lotto-live` from sitemap generation
- Game-related pages (M7/MLBB) preserved

### ⚠️ PRESERVED (for other apps)
- **All API routes** — `/api/v1/lottery/*`, `/api/sport/*` still working
- **All database tables** — `lottery_*`, `sport_*`, `m7_*` intact
- **All models and services** — Code still exists, just web routes disabled

---

## Files Created in Phase 1 Prep

### Documentation
| File | Purpose |
|------|---------|
| `docs/seo/PHASE1_AUDIT_REPORT.md` | Chi tiết audit findings |
| `docs/seo/PHASE1_IMPLEMENTATION_ROADMAP.md` | Roadmap triển khai |
| `docs/seo/PHASE1_QUICK_REFERENCE.md` | File này |

### Backend
| File | Purpose |
|------|---------|
| `app/Console/Commands/ExportUrlsForAudit.php` | Export URLs cho audit |
| `app/Services/SiteMetricsService.php` | Dynamic metrics từ DB |
| `app/Models/Author.php` | Author model cho E-E-A-T |
| `database/migrations/2026_08_14_000001_create_authors_table.php` | Author table |

### Frontend
| File | Purpose |
|------|---------|
| `resources/views/components/author-box.blade.php` | Author component |
| `resources/views/lamgame/pages/gioi-thieu-v2.blade.php` | About page mới |

---

## Sprint 1 Priority Tasks

### 1. Export & Backup (Day 1-2)
```bash
# Export URLs
php artisan seo:export-urls

# Backup lottery/sport data
mysqldump lamgame lottery_* m7_* sport_* > backup_20260814.sql

# Archive code
git checkout -b archive/lottery-sport-features
git add app/Services/Lottery app/Models/Lottery* app/Models/M7*
git commit -m "Archive lottery/sport before Phase 1 cleanup"
```

### 2. Navigation Cleanup (Day 3)

**File:** `resources/views/partials/footer-redesign.blade.php`

Remove lines 57-60:
```blade
{{-- REMOVE THESE --}}
<li><a href="{{ route('sport.index') }}">Thể thao</a></li>
<li><a href="{{ route('lottery.index') }}">Xổ số</a></li>
<li><a href="{{ route('world-cup-2026') }}">World Cup 2026</a></li>
```

### 3. Sitemap Cleanup (Day 4)
- Remove `/xo-so/*` từ sitemap
- Remove `/the-thao/*` từ sitemap
- Remove `/p/lottolive` từ sitemap
- Regenerate sitemaps

### 4. Scheduler Cleanup (Day 5)

**File:** `app/Console/Kernel.php`

Comment out lottery jobs:
```php
// DISABLED — Phase 1 cleanup
// $schedule->job(new ScrapeVietlotLottery)->...;
// $schedule->job(new ScrapeTraditionalLottery)->...;
```

---

## GSC Queries to Monitor

```
# Check indexing
site:lamgame.vn

# Check removed content (should decrease)
site:lamgame.vn/xo-so
site:lamgame.vn/the-thao
site:lamgame.vn/p/lottolive

# Check core content (should be stable/increase)
site:lamgame.vn/source-game
site:lamgame.vn/blog
site:lamgame.vn/viec-lam-game
site:lamgame.vn/forum
```

---

## Acceptance Checklist

### Sprint 1 Complete When:
- [ ] URL audit spreadsheet complete
- [ ] GSC data exported
- [ ] Code backed up
- [ ] Footer navigation cleaned
- [ ] Sitemap regenerated
- [ ] Scheduler updated
- [ ] No lottery/sport links in main UI

### Phase 1 Complete When:
- [ ] About page reflects ecosystem positioning
- [ ] No unverifiable statistics
- [ ] Author system implemented
- [ ] Editorial policy published
- [ ] Redirects executed
- [ ] GSC shows no new errors
- [ ] Traffic stable or improved

---

## Key Contacts

| Role | Responsibility |
|------|----------------|
| SEO/Marketing | URL audit, content classification, monitoring |
| Backend | Sitemap, redirects, scheduler, metrics service |
| Frontend | Navigation, About page, author component |
| Content | About rewrite, editorial policy, fact-checking |

---

## Quick Links

- GSC: https://search.google.com/search-console
- GA4: https://analytics.google.com
- Sitemap: https://lamgame.vn/sitemap.xml
- robots.txt: https://lamgame.vn/robots.txt

---

**Last Updated:** 14/08/2026
