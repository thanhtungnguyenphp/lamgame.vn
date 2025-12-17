# 🚀 QUICK REFERENCE - SOURCE GAME

## 📍 URLs

```
Danh sách:  /source-game
Chi tiết:   /source-game/{slug}
```

## 🎯 Chức năng chính

### Hiện tại (Phase 1) ✅
- Browse & search source games
- View detail với tabs (Description, Features, Technical, Installation, Reviews)
- Add to cart & checkout
- Download source code
- Review & rating

### Sắp tới (Phase 2) 🔄
- Seller registration
- Upload source game
- Seller dashboard
- Revenue sharing
- Withdrawal system

## 🗄️ Database Tables

### Existing (Bagisto)
```sql
products                      -- Sản phẩm
product_flat                  -- Dữ liệu flat
product_categories            -- Categories
product_images                -- Images
product_downloadable_links    -- Download files
product_attribute_values      -- Attributes
```

### New (Cần tạo)
```sql
source_game_sellers           -- Seller info
source_game_versions          -- Version history
source_game_licenses          -- License types
source_game_earnings          -- Earnings tracking
source_game_withdrawals       -- Withdrawal requests
```

## 🔌 API Endpoints

### Public
```
GET  /api/source-games              # List with filters
GET  /api/source-games/{slug}       # Detail
```

### Seller (Auth required)
```
POST /api/seller/source-games       # Create
PUT  /api/seller/source-games/{id}  # Update
GET  /api/seller/dashboard          # Dashboard stats
POST /api/seller/withdrawals        # Request withdrawal
```

### Admin
```
GET  /api/admin/source-games/pending    # Pending review
POST /api/admin/source-games/{id}/approve
POST /api/admin/source-games/{id}/reject
```

## 📂 File Structure

```
app/
├── Http/Controllers/
│   └── LamGamePageController.php    # Main controller
├── Models/
│   ├── SourceGameSeller.php         # (New)
│   ├── SourceGameEarning.php        # (New)
│   └── SourceGameWithdrawal.php     # (New)
└── Services/
    ├── SourceGameUploadService.php  # (New)
    └── EarningService.php           # (New)

resources/
└── themes/emsaigon/views/
    └── products/
        ├── source-game-view.blade.php
        └── source-game-content.blade.php

routes/
└── web.php                          # Routes

database/
└── migrations/
    ├── create_source_game_sellers_table.php      # (New)
    ├── create_source_game_earnings_table.php     # (New)
    └── create_source_game_withdrawals_table.php  # (New)
```

## 🔧 Key Functions

### Controller Methods
```php
// LamGamePageController.php
sourceGame()              // List source games
sourceGameDetail($slug)   // Show detail
```

### Service Methods (Cần tạo)
```php
// SourceGameUploadService.php
processUpload($files)     // Process file upload
validateFiles($files)     // Validate & scan
generateThumbnails()      // Generate previews

// EarningService.php
calculateEarning($order)  // Calculate commission
processWithdrawal($id)    // Process payout
```

## 💡 Quick Commands

### Development
```bash
# Run migrations
php artisan migrate

# Seed data
php artisan db:seed --class=SourceGameSeeder

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Queue worker
php artisan queue:work --queue=default,images,analytics
```

### Testing
```bash
# Run tests
php artisan test

# Specific test
php artisan test --filter=SourceGameTest

# With coverage
php artisan test --coverage
```

### Deployment
```bash
# Build assets
npm run production

# Optimize
php artisan optimize
php artisan route:cache
php artisan config:cache
php artisan view:cache

# Deploy
git push production main
```

## 📊 Performance Targets

```
Page Load:        < 2s
API Response:     < 200ms
Database Query:   < 50ms
Cache Hit Rate:   > 80%
Upload Speed:     > 10MB/s
Download Speed:   > 5MB/s
```

## 🔐 Security Checklist

- [ ] File type validation
- [ ] Virus scanning
- [ ] Size limits enforced
- [ ] Signed download URLs
- [ ] Rate limiting enabled
- [ ] CSRF protection
- [ ] XSS prevention
- [ ] SQL injection prevention
- [ ] Payment gateway secured
- [ ] SSL/TLS enabled

## 🐛 Common Issues

### Upload fails
```bash
# Check permissions
chmod -R 775 storage/
chown -R www-data:www-data storage/

# Check disk space
df -h

# Check upload limits
php -i | grep upload_max_filesize
php -i | grep post_max_size
```

### Download link expired
```php
// Regenerate link
$url = Storage::disk('s3')->temporaryUrl(
    $path,
    now()->addHours(24)
);
```

### Cache issues
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
redis-cli FLUSHALL
```

## 📞 Support

**Email:** support@lamgame.vn  
**Phone:** 0908 123 456  
**Docs:** `/docs/source_game/`

## 🔗 Quick Links

- [Full Documentation](./README.md)
- [Technical Details](./02_KY_THUAT.md)
- [Development Plan](./03_KE_HOACH_PHAT_TRIEN.md)
- [Optimization Guide](./04_TOI_UU_HOA.md)

---

**Last updated:** 2025-12-16
