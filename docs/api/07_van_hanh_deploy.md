# Lotto Live — Hướng Dẫn Vận Hành & Deploy

> Cập nhật: 22/03/2026

---

## 1. TỔNG QUAN HỆ THỐNG

### Kiến trúc

```
Flutter App ──→ Laravel API (lamgame.vn) ──→ MySQL + Redis
                      │
                      ├── Scheduler (cron) ──→ Scrape KQXS tự động
                      ├── FCM Push ──→ Firebase Cloud Messaging v1
                      └── Queue Worker ──→ Dò vé + gửi push kết quả
```

### Thành phần

| Thành phần | Mô tả |
|-----------|-------|
| `lottery:scrape` | Artisan command scrape KQXS + push FCM + dò vé |
| `ScrapeVietlotLottery` | Job scrape Vietlot (Mega, Power, Max3D, Keno) |
| `CheckUserTickets` | Job tự động dò vé pending + push kết quả |
| `FcmNotificationService` | Gửi push qua FCM v1 API (OAuth2 + JWT) |
| `LotteryCheckService` | Logic dò số (so sánh N chữ số cuối) |

---

## 2. LỊCH TỰ ĐỘNG (Scheduler)

### XS Truyền thống

| Miền | Giờ quay | Scrape retry | Tần suất | Max retry |
|------|----------|-------------|----------|-----------|
| Nam | 16:15 | 16:35 → 17:15 | Mỗi 5 phút | 8 lần |
| Trung | 17:15 | 17:35 → 18:15 | Mỗi 5 phút | 8 lần |
| Bắc | 18:15 | 18:35 → 19:15 | Mỗi 5 phút | 8 lần |

### Vietlot

| Game | Scrape retry | Tần suất |
|------|-------------|----------|
| Mega, Power, Max3D, Max3D Pro | 18:05 → 18:45 | Mỗi 5 phút |
| Keno | 06:00 → 22:00 | Mỗi 10 phút |

### Cơ chế thông minh
- Khi scrape thành công → set cache flag → các lần retry sau tự **skip**
- Nếu DB đã có kết quả → skip luôn (không scrape lại)
- `withoutOverlapping()` → không chạy trùng nếu lần trước chưa xong
- Log output vào `storage/logs/lottery-scrape.log`

---

## 3. DEPLOY

### 3.1 Sau khi push code mới

```bash
# Clear cache
docker exec -u root lg-php php artisan config:clear
docker exec -u root lg-php php artisan cache:clear

# Migrate (nếu có migration mới)
docker exec -u root lg-php php artisan migrate

# Restart queue worker
docker exec -u root lg-php php artisan queue:restart
```

### 3.2 Kiểm tra scheduler

```bash
# Xem danh sách scheduled tasks
docker exec -u root lg-php php artisan schedule:list

# Chạy scheduler thủ công 1 lần (test)
docker exec -u root lg-php php artisan schedule:run
```

### 3.3 Kiểm tra cron đang chạy

```bash
# Trong container
docker exec -u root lg-php crontab -l

# Phải có dòng:
# * * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1
```

Nếu chưa có cron, thêm:

```bash
docker exec -u root lg-php bash -c 'echo "* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1" | crontab -'
docker exec -u root lg-php service cron start
```

---

## 4. CHẠY THỦ CÔNG

### 4.1 Scrape KQXS

```bash
# Scrape miền nam hôm nay
docker exec -u root lg-php php artisan lottery:scrape --region=mien-nam

# Scrape ngày cụ thể
docker exec -u root lg-php php artisan lottery:scrape --region=mien-nam --date=2026-03-22

# Scrape tất cả miền
docker exec -u root lg-php php artisan lottery:scrape --region=all

# Force scrape lại (bỏ qua cache, scrape + push FCM lại)
docker exec -u root lg-php php artisan lottery:scrape --region=mien-nam --force
```

### 4.2 Test FCM Push

```bash
# Push đến topic
docker exec -u root lg-php php artisan tinker --execute="\$s = app(\App\Services\FcmNotificationService::class); echo \$s->sendToTopic('kqxs_mien_nam', ['title'=>'Test','body'=>'Test push'], ['type'=>'test']) ? 'OK' : 'FAILED';"

# Push đến device cụ thể (thay FCM_TOKEN)
docker exec -u root lg-php php artisan tinker --execute="\$s = app(\App\Services\FcmNotificationService::class); echo \$s->sendToToken('FCM_TOKEN_HERE', ['title'=>'Test','body'=>'Test device push'], ['type'=>'test']) ? 'OK' : 'FAILED';"
```

### 4.3 Dò vé thủ công

```bash
# Dò tất cả vé pending miền nam hôm nay
docker exec -u root lg-php php artisan tinker --execute="App\Jobs\CheckUserTickets::dispatchSync('mien-nam', '2026-03-22');"
```

---

## 5. DEBUG & LOG

### 5.1 Xem log

```bash
# Log scrape KQXS
docker exec -u root lg-php tail -50 /var/www/html/storage/logs/lottery-scrape.log

# Log Laravel chung (FCM errors, job failures...)
docker exec -u root lg-php tail -100 /var/www/html/storage/logs/laravel.log

# Filter log FCM
docker exec -u root lg-php grep -A3 "FCM" /var/www/html/storage/logs/laravel.log | tail -30

# Filter log lottery
docker exec -u root lg-php grep -A3 "Lottery\|lottery" /var/www/html/storage/logs/laravel.log | tail -30
```

### 5.2 Kiểm tra trạng thái

```bash
# Kiểm tra cache flag (đã scrape chưa)
docker exec -u root lg-php php artisan tinker --execute="echo Cache::get('lottery:scraped:mien-nam:2026-03-22') ? 'DA SCRAPE' : 'CHUA SCRAPE';"

# Kiểm tra DB có kết quả chưa
docker exec -u root lg-php php artisan tinker --execute="echo App\Models\LotteryDraw::traditional()->forRegion('mien-nam')->forDate('2026-03-22')->completed()->count() . ' draws';"

# Kiểm tra vé pending
docker exec -u root lg-php php artisan tinker --execute="echo App\Models\UserTicket::pending()->count() . ' pending tickets';"

# Kiểm tra vé theo ngày
docker exec -u root lg-php php artisan tinker --execute="echo App\Models\UserTicket::forDate('2026-03-22')->get()->groupBy('status')->map->count();"
```

### 5.3 Kiểm tra queue

```bash
# Queue worker đang chạy không
docker exec -u root lg-php ps aux | grep queue

# Xem failed jobs
docker exec -u root lg-php php artisan queue:failed

# Retry failed jobs
docker exec -u root lg-php php artisan queue:retry all
```

### 5.4 Xóa cache để test lại

```bash
# Xóa cache flag 1 region/date (để scrape lại)
docker exec -u root lg-php php artisan tinker --execute="Cache::forget('lottery:scraped:mien-nam:2026-03-22'); echo 'CLEARED';"

# Hoặc dùng --force
docker exec -u root lg-php php artisan lottery:scrape --region=mien-nam --date=2026-03-22 --force
```

---

## 6. FIREBASE CREDENTIALS

### File location
```
Container: /var/www/html/storage/app/firebase-credentials.json
Server:    /data/www/lamgame.vn/storage/app/firebase-credentials.json
```

### Kiểm tra

```bash
# File tồn tại
docker exec -u root lg-php ls -la /var/www/html/storage/app/firebase-credentials.json

# Nội dung hợp lệ
docker exec -u root lg-php php -r "\$d = json_decode(file_get_contents('/var/www/html/storage/app/firebase-credentials.json'), true); echo 'project: ' . \$d['project_id'] . PHP_EOL . 'email: ' . \$d['client_email'];"
```

### Thay key mới

1. Firebase Console → Project Settings → Service accounts → Generate new private key
2. Copy nội dung vào server:
```bash
nano /data/www/lamgame.vn/storage/app/firebase-credentials.json
# Paste nội dung → Ctrl+O → Enter → Ctrl+X
docker cp /data/www/lamgame.vn/storage/app/firebase-credentials.json lg-php:/var/www/html/storage/app/firebase-credentials.json
docker exec -u root lg-php php artisan cache:clear
```

### .env config

```env
FIREBASE_PROJECT_ID=lotto-live-vn
FIREBASE_CREDENTIALS=/var/www/html/storage/app/firebase-credentials.json
```

---

## 7. API ENDPOINTS

| Method | Endpoint | Mô tả |
|--------|----------|-------|
| `GET` | `/api/v1/lottery/health` | Health check |
| `GET` | `/api/v1/lottery/latest` | Tất cả KQ mới nhất |
| `GET` | `/api/v1/lottery/traditional` | XS truyền thống (3 miền) |
| `GET` | `/api/v1/lottery/vietlot` | Vietlot (5 games) |
| `GET` | `/api/v1/lottery/schedule` | Lịch quay số |
| `POST` | `/api/v1/lottery/check` | Dò số tự động |
| `POST` | `/api/v1/user/tickets` | Đăng ký vé số |
| `GET` | `/api/v1/user/tickets` | Danh sách vé |
| `GET` | `/api/v1/user/tickets/{id}` | Chi tiết vé |
| `DELETE` | `/api/v1/user/tickets/{id}` | Xóa vé |

Chi tiết API: xem `docs/api/06_mobile_app_api.md`
Postman: import `docs/api/LottoLive_API.postman_collection.json`

---

## 8. FCM TOPICS

App Flutter cần subscribe:

| Topic | Push khi |
|-------|----------|
| `kqxs_mien_nam` | Có KQXS Miền Nam |
| `kqxs_mien_trung` | Có KQXS Miền Trung |
| `kqxs_mien_bac` | Có KQXS Miền Bắc |
| `vietlot` | Có KQ Vietlot (trừ Keno) |

Push kết quả dò vé → gửi trực tiếp đến `fcm_token` của từng vé.

---

## 9. TROUBLESHOOTING

### FCM push không gửi được

```bash
# Kiểm tra file credentials
docker exec -u root lg-php php -r "\$d = json_decode(file_get_contents('/var/www/html/storage/app/firebase-credentials.json'), true); echo implode(', ', array_keys(\$d ?? []));"
# Phải có: type, project_id, private_key_id, private_key, client_email, client_id, auth_uri, token_uri, ...

# Xem lỗi FCM trong log
docker exec -u root lg-php grep "FCM\|Firebase\|firebase" /var/www/html/storage/logs/laravel.log | tail -20
```

### Scrape không có kết quả

```bash
# Xem scrape log
docker exec -u root lg-php grep "FAILED\|failed" /var/www/html/storage/logs/lottery-scrape.log | tail -10

# Kiểm tra scrape log trong DB
docker exec -u root lg-php php artisan tinker --execute="App\Models\LotteryScrapeLog::latest()->take(5)->get(['source','status','error_message','created_at'])->each(fn(\$l) => print(\$l->source.' '.\$l->status.' '.\$l->error_message.' '.\$l->created_at.PHP_EOL));"
```

### Vé không được dò

```bash
# Kiểm tra vé pending
docker exec -u root lg-php php artisan tinker --execute="App\Models\UserTicket::pending()->get(['ticket_id','region','draw_date'])->each(fn(\$t) => print(\$t->ticket_id.' '.\$t->region.' '.\$t->draw_date.PHP_EOL));"

# Dò thủ công
docker exec -u root lg-php php artisan tinker --execute="App\Jobs\CheckUserTickets::dispatchSync('mien-nam', date('Y-m-d'));"
```

### Scheduler không chạy

```bash
# Kiểm tra cron
docker exec -u root lg-php crontab -l

# Test chạy scheduler 1 lần
docker exec -u root lg-php php artisan schedule:run

# Kiểm tra timezone
docker exec -u root lg-php php artisan tinker --execute="echo now()->timezone->getName() . ' ' . now()->format('H:i:s');"
```
