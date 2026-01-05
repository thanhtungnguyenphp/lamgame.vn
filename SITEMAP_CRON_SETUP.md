# Sitemap Auto-Regeneration Setup

## Cron Job Configuration

Sitemap được tự động regenerate **mỗi ngày lúc 2:00 sáng**.

### Cron Schedule
```bash
0 2 * * * /root/lamgame-sitemap-regenerate.sh
```

### Script Location
- **Script**: `/root/lamgame-sitemap-regenerate.sh`
- **Log file**: `/var/log/lamgame-sitemap.log`
- **Sitemap**: `/data/www/lamgame.vn/public/sitemap.xml`

## Quản lý Cron Job

### Xem cron jobs hiện tại
```bash
crontab -l
```

### Chỉnh sửa cron jobs
```bash
crontab -e
```

### Xem log của sitemap regeneration
```bash
cat /var/log/lamgame-sitemap.log
# hoặc xem 20 dòng cuối
tail -20 /var/log/lamgame-sitemap.log
```

### Chạy thủ công (để test)
```bash
/root/lamgame-sitemap-regenerate.sh
```

## Script Content

Script thực hiện:
1. Chạy command: `docker exec lg-php php artisan sitemap:generate`
2. Ghi log kết quả vào `/var/log/lamgame-sitemap.log`
3. Kiểm tra và ghi lại kích thước file sitemap

## Troubleshooting

### Sitemap không được update
```bash
# Kiểm tra cron service
systemctl status cron

# Kiểm tra log
tail -50 /var/log/lamgame-sitemap.log

# Test script thủ công
/root/lamgame-sitemap-regenerate.sh
```

### Log file quá lớn
```bash
# Xem kích thước log
ls -lh /var/log/lamgame-sitemap.log

# Xóa log cũ (nếu cần)
> /var/log/lamgame-sitemap.log
```

## Sitemap URLs

- **Public URL**: https://lamgame.vn/sitemap.xml
- **File path**: `/data/www/lamgame.vn/public/sitemap.xml`
- **Declared in**: `/data/www/lamgame.vn/public/robots.txt`

## Google Search Console

Sitemap đã được submit tới Google Search Console:
- URL: https://lamgame.vn/sitemap.xml
- Theo dõi index status tại: https://search.google.com/search-console

## Notes

- Script chạy trong container `lg-php`
- Sitemap bao gồm: jobs, blogs, static pages
- Thời gian chạy: ~2-5 giây
- Không cần restart Docker containers
