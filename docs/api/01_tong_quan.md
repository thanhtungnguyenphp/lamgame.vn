# Lotto Live API — Tổng Quan

## 1. Mục tiêu

Cung cấp REST API trả kết quả xổ số Việt Nam (truyền thống 3 miền + Vietlot) dưới dạng JSON chuẩn cho app Lotto Live.

## 2. Kiến trúc

```
┌──────────────┐     ┌──────────────────┐     ┌─────────────────┐
│  Flutter App  │────▶│   Go API Server  │────▶│  Nguồn dữ liệu  │
│  (Client)     │◀────│   (REST JSON)    │◀────│  (Scrape HTML)   │
└──────────────┘     └──────────────────┘     └─────────────────┘
                              │
                              ▼
                     ┌──────────────────┐
                     │   Cache Layer    │
                     │  (Redis/Memory)  │
                     └──────────────────┘
```

## 3. Tech Stack

| Thành phần | Công nghệ |
|---|---|
| Language | Go 1.22+ |
| HTTP Framework | net/http hoặc gin/chi |
| HTML Parser | goquery |
| Cache | In-memory (sync.Map) hoặc Redis |
| Deploy | Docker → VPS / Cloud Run |

## 4. Base URL

```
Production:  https://api.lottolive.vn/api/v1
Staging:     https://staging-api.lottolive.vn/api/v1
Local:       http://localhost:8080/api/v1
```

## 5. Danh sách Endpoints

| Method | Endpoint | Mô tả |
|---|---|---|
| `GET` | `/health` | Health check |
| `GET` | `/lottery/latest` | Tất cả kết quả mới nhất |
| `GET` | `/lottery/traditional` | XS truyền thống (3 miền) |
| `GET` | `/lottery/vietlot` | Vietlot (Mega, Power, Max3D, Keno...) |
| `GET` | `/lottery/schedule` | Lịch quay số |

## 6. Response Format Chung

### Thành công (200)
```json
{
  "status": "ok",
  "data": { ... },
  "meta": {
    "cached": true,
    "fetched_at": "2026-02-28T16:30:00+07:00"
  }
}
```

### Lỗi (4xx/5xx)
```json
{
  "status": "error",
  "error": {
    "code": "INVALID_DATE",
    "message": "Ngày không hợp lệ. Format: YYYY-MM-DD"
  }
}
```

## 7. Headers

### Request
```
Accept: application/json
X-App-Version: 1.0.0  (optional)
```

### Response
```
Content-Type: application/json; charset=utf-8
X-Cache: HIT|MISS
X-Response-Time: 120ms
```

## 8. Rate Limiting

| Client | Limit |
|---|---|
| App (có API key) | 100 req/phút |
| Public | 30 req/phút |

Response khi bị limit:
```
HTTP 429 Too Many Requests
Retry-After: 30
```

## 9. Nguồn dữ liệu Scrape

| Nguồn | URL Pattern | Dữ liệu | Ưu tiên |
|---|---|---|---|
| xoso.com.vn | `/xsmn-DD-MM-YYYY.html` | XS truyền thống 3 miền | Chính |
| vietlott.vn | `/vi/trung-thuong/ket-qua-trung-thuong/mega-645` | Vietlot | Chính |
| xosodaiphat.com | Tương tự | Backup truyền thống | Dự phòng |
| minhngoc.net.vn | Tương tự | Backup | Dự phòng |

## 10. Cache Strategy

| Loại data | TTL | Ghi chú |
|---|---|---|
| Kết quả hôm nay (trước giờ quay) | 5 phút | Chờ kết quả |
| Kết quả hôm nay (sau giờ quay) | 1 giờ | Đã có kết quả |
| Kết quả ngày trước | 24 giờ | Không đổi |
| Keno | 2 phút | Quay mỗi 10 phút |
| Lịch quay | 7 ngày | Cố định |

## 11. Giờ quay số

| Miền/Game | Giờ quay | Timezone |
|---|---|---|
| Miền Nam | 16:15 | UTC+7 |
| Miền Trung | 17:15 | UTC+7 |
| Miền Bắc | 18:15 | UTC+7 |
| Vietlot Mega/Power/Max | 18:00 | UTC+7 |
| Vietlot Keno | 06:00–21:55 (mỗi 10 phút) | UTC+7 |
