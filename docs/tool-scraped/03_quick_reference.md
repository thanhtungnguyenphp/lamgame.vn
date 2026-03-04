# Quick Reference — Lottery Scrape Tool

## Commands

### `lottery:scrape` — Chỉ scrape data

```bash
php artisan lottery:scrape                                    # Tất cả miền, hôm nay
php artisan lottery:scrape --region=mien-nam                  # 1 miền, hôm nay
php artisan lottery:scrape --region=mien-nam --date=2026-03-04  # 1 miền, ngày cụ thể
```

### `lottery:scrape-migrate` — Scrape + tạo JSON + migration (⭐ chính)

```bash
# Scrape hôm nay + tạo migration
php artisan lottery:scrape-migrate --region=mien-nam

# Scrape 7 ngày gần nhất
php artisan lottery:scrape-migrate --region=mien-nam --days=7

# Tất cả miền, 3 ngày
php artisan lottery:scrape-migrate --region=all --days=3

# Chỉ scrape, không tạo migration
php artisan lottery:scrape-migrate --region=mien-nam --scrape-only
```

**Output:**
- `database/seeders/data/lottery_{region}_{dates}.json` — Data JSON
- `database/migrations/{timestamp}_seed_lottery_{region}_{dates}.php` — Migration

## Workflow hàng ngày

```bash
# 1. Sau giờ quay, chạy trong Docker:
docker exec lamgame-php php artisan lottery:scrape-migrate --region=mien-nam

# 2. Commit files mới:
git add database/seeders/data/ database/migrations/
git commit -m "seed: lottery mien-nam YYYY-MM-DD"

# 3. Deploy:
php artisan migrate
```

## Workflow bù data nhiều ngày

```bash
docker exec lamgame-php php artisan lottery:scrape-migrate --region=mien-nam --days=7 --date=2026-03-04
```

## Giờ quay số

| Miền | Giờ | Ghi chú |
|---|---|---|
| Miền Nam | 16:15 | 3-4 đài/ngày |
| Miền Trung | 17:15 | 2-3 đài/ngày |
| Miền Bắc | 18:15 | 1 đài (Hà Nội) |

## Troubleshooting

| Lỗi | Nguyên nhân | Fix |
|---|---|---|
| `❌ FAILED` | Chưa đến giờ quay | Chờ sau giờ quay |
| `No results parsed` | HTML structure thay đổi | Kiểm tra `TraditionalScraper` |
| Province không match | Tên tỉnh mới | Thêm alias trong `getProvinceNameToCodeMap()` |
| API trả 404 | Chưa có data | Chạy scrape trước |
