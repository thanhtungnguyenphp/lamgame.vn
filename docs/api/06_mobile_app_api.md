# Lotto Live Mobile App — API Documentation

> Cập nhật: 22/03/2026
> Base URL: `https://lamgame.vn/api/v1`

---

## Tổng quan

Tài liệu này mô tả các API phục vụ cho mobile app Lotto Live (Flutter).

### Response Format

```json
// Thành công
{"status": "ok", "data": {...}}

// Lỗi
{"status": "error", "error": {"code": "ERROR_CODE", "message": "Mô tả lỗi"}}
```

### Rate Limiting

| Endpoint | Limit |
|----------|-------|
| Lottery (GET) | 60 req/phút |
| Lottery Check (POST) | 30 req/phút |
| User Tickets | 30 req/phút |

---

## 1. KẾT QUẢ XỔ SỐ

### 1.1 Health Check

```
GET /lottery/health
```

Response:
```json
{
  "status": "ok",
  "version": "1.0.0",
  "sources": {
    "xoso_com_vn": "healthy",
    "vietlott_vn": "healthy"
  }
}
```

### 1.2 Kết quả mới nhất

```
GET /lottery/latest
GET /lottery/latest?include=traditional
GET /lottery/latest?include=vietlot
```

| Param | Type | Required | Mô tả |
|-------|------|----------|-------|
| include | string | ❌ | `all` (default), `traditional`, `vietlot` |

### 1.3 XS Truyền thống

```
GET /lottery/traditional?region=mien-nam
GET /lottery/traditional?region=mien-nam&date=2026-03-22
GET /lottery/traditional?region=mien-nam&date=2026-03-22&province=HCM
```

| Param | Type | Required | Mô tả |
|-------|------|----------|-------|
| region | string | ✅ | `mien-nam`, `mien-trung`, `mien-bac` |
| date | string | ❌ | Format `YYYY-MM-DD`. Default: hôm nay |
| province | string | ❌ | Mã đài: `HCM`, `DN`, `VL`... |

Response:
```json
{
  "status": "ok",
  "data": {
    "date": "2026-03-22",
    "region": "mien-nam",
    "region_name": "Miền Nam",
    "draw_time": "16:15",
    "results": [
      {
        "province": "TP. Hồ Chí Minh",
        "province_code": "HCM",
        "prizes": {
          "giai_db": ["123456"],
          "giai_1": ["12345"],
          "giai_2": ["12345"],
          "giai_3": ["12345", "67890"],
          "giai_4": ["12345", "67890", "11111", "22222", "33333", "44444", "55555"],
          "giai_5": ["1234"],
          "giai_6": ["1234", "5678", "9012"],
          "giai_7": ["123"],
          "giai_8": ["12"]
        }
      }
    ]
  },
  "meta": {"cached": true, "fetched_at": "2026-03-22T16:30:00+07:00"}
}
```

### 1.4 Vietlot

```
GET /lottery/vietlot?game=mega645
GET /lottery/vietlot?game=power655&date=2026-03-22
GET /lottery/vietlot?game=keno&period=latest
GET /lottery/vietlot?game=keno&date=2026-03-22
```

| Param | Type | Required | Mô tả |
|-------|------|----------|-------|
| game | string | ✅ | `mega645`, `power655`, `max3d`, `max3d_pro`, `keno` |
| date | string | ❌ | Format `YYYY-MM-DD` |
| period | string | ❌ | Kỳ Keno cụ thể hoặc `latest` |

### 1.5 Lịch quay số

```
GET /lottery/schedule
GET /lottery/schedule?date=2026-03-22&type=traditional
```

| Param | Type | Required | Mô tả |
|-------|------|----------|-------|
| date | string | ❌ | Format `YYYY-MM-DD`. Default: hôm nay |
| type | string | ❌ | `all` (default), `traditional`, `vietlot` |

---

## 2. DÒ SỐ TỰ ĐỘNG

### 2.1 Dò số

```
POST /lottery/check
Content-Type: application/json
```

Request:
```json
{
  "numbers": ["123456", "56"],
  "region": "mien-nam",
  "date": "2026-03-22",
  "province_code": "HCM"
}
```

| Field | Type | Required | Mô tả |
|-------|------|----------|-------|
| numbers | string[] | ✅ | Danh sách số cần dò (2-6 chữ số), tối đa 20 số |
| region | string | ✅ | `mien-nam`, `mien-trung`, `mien-bac` |
| date | string | ✅ | Ngày xổ `YYYY-MM-DD` |
| province_code | string | ❌ | Mã đài. Null = dò tất cả đài |

Response:
```json
{
  "status": "ok",
  "data": {
    "date": "2026-03-22",
    "region": "mien-nam",
    "matches": [
      {
        "number": "56",
        "province": "TP. Hồ Chí Minh",
        "province_code": "HCM",
        "prize": "giai_7",
        "prize_name": "Giải 7",
        "matched_number": "56",
        "full_prize_number": "456"
      }
    ],
    "total_matches": 1
  }
}
```

Logic dò: so sánh **N chữ số cuối** của số cần dò với từng số trong bảng kết quả. Ví dụ số `56` (2 chữ số) sẽ match với bất kỳ số giải nào có 2 số cuối là `56`.

---

## 3. ĐĂNG KÝ VÉ SỐ (Auto dò + Push)

### 3.1 Đăng ký vé

```
POST /user/tickets
Content-Type: application/json
```

Request:
```json
{
  "fcm_token": "dK8x...",
  "numbers": ["123456"],
  "region": "mien-nam",
  "province_code": "HCM",
  "draw_date": "2026-03-22"
}
```

| Field | Type | Required | Mô tả |
|-------|------|----------|-------|
| fcm_token | string | ✅ | FCM device token |
| numbers | string[] | ✅ | Danh sách số trên vé (2-6 chữ số), tối đa 20 |
| region | string | ✅ | `mien-nam`, `mien-trung`, `mien-bac` |
| province_code | string | ❌ | Mã đài |
| draw_date | string | ✅ | Ngày xổ `YYYY-MM-DD` (phải >= hôm nay) |

Response:
```json
{
  "status": "ok",
  "data": {
    "ticket_id": "t_abc123def4",
    "status": "pending"
  }
}
```

### 3.2 Danh sách vé đã đăng ký

```
GET /user/tickets?fcm_token=dK8x...
GET /user/tickets?fcm_token=dK8x...&status=won
```

| Param | Type | Required | Mô tả |
|-------|------|----------|-------|
| fcm_token | string | ✅ | FCM device token |
| status | string | ❌ | Filter: `pending`, `won`, `lost` |

Response:
```json
{
  "status": "ok",
  "data": [
    {
      "ticket_id": "t_abc123def4",
      "numbers": ["123456"],
      "region": "mien-nam",
      "province_code": "HCM",
      "draw_date": "2026-03-22",
      "status": "won",
      "matched_prizes": [
        {
          "number": "56",
          "province": "TP. Hồ Chí Minh",
          "province_code": "HCM",
          "prize": "giai_7",
          "prize_name": "Giải 7",
          "matched_number": "56",
          "full_prize_number": "456"
        }
      ]
    }
  ]
}
```

### 3.3 Chi tiết vé

```
GET /user/tickets/{ticket_id}
```

Response:
```json
{
  "status": "ok",
  "data": {
    "ticket_id": "t_abc123def4",
    "numbers": ["123456"],
    "region": "mien-nam",
    "province_code": "HCM",
    "draw_date": "2026-03-22",
    "status": "won",
    "matched_prizes": [...],
    "created_at": "2026-03-22T10:00:00+07:00"
  }
}
```

### 3.4 Xóa vé

```
DELETE /user/tickets/{ticket_id}
```

Response:
```json
{"status": "ok"}
```

---

## 4. FCM PUSH NOTIFICATIONS

App cần subscribe các FCM topics sau:

| Topic | Khi nào nhận push |
|-------|-------------------|
| `kqxs_mien_nam` | Có KQXS Miền Nam (~16:20 hàng ngày) |
| `kqxs_mien_trung` | Có KQXS Miền Trung (~17:20 hàng ngày) |
| `kqxs_mien_bac` | Có KQXS Miền Bắc (~18:20 hàng ngày) |
| `vietlot` | Có KQ Vietlot (trừ Keno) |

### 4.1 Push KQXS mới

```json
{
  "notification": {
    "title": "KQXS Miền Nam - 22/03/2026",
    "body": "TP.HCM: ĐB 123456 | Đồng Nai: ĐB 789012"
  },
  "data": {
    "type": "kqxs",
    "region": "mien-nam",
    "date": "2026-03-22"
  }
}
```

App xử lý `data.type == "kqxs"` → navigate đến màn hình KQXS theo `region` + `date`.

### 4.2 Push kết quả dò vé

Trúng:
```json
{
  "notification": {
    "title": "🎉 Vé 123456 trúng Giải 7!",
    "body": "TP. Hồ Chí Minh - 22/03/2026. Tap để xem chi tiết."
  },
  "data": {
    "type": "ticket_result",
    "ticket_id": "t_abc123def4",
    "status": "won",
    "matches": "[{\"prize\":\"giai_7\",\"number\":\"123456\"}]"
  }
}
```

Không trúng:
```json
{
  "notification": {
    "title": "Kết quả dò vé 123456",
    "body": "Không trúng giải. TP. Hồ Chí Minh - 22/03/2026"
  },
  "data": {
    "type": "ticket_result",
    "ticket_id": "t_abc123def4",
    "status": "lost"
  }
}
```

App xử lý `data.type == "ticket_result"` → navigate đến chi tiết vé theo `ticket_id`.

---

## 5. MÃ ĐÀI (Province Codes)

### Miền Nam
| Code | Tên |
|------|-----|
| HCM | TP. Hồ Chí Minh |
| DT | Đồng Tháp |
| CM | Cà Mau |
| BT | Bến Tre |
| VT | Vũng Tàu |
| BL | Bạc Liêu |
| DN | Đồng Nai |
| CT | Cần Thơ |
| ST | Sóc Trăng |
| AG | An Giang |
| BTN | Bình Thuận |
| TN | Tây Ninh |
| BD | Bình Dương |
| TG | Tiền Giang |
| KG | Kiên Giang |
| DL | Đà Lạt |
| BP | Bình Phước |
| HG | Hậu Giang |
| LA | Long An |
| VL | Vĩnh Long |
| TV | Trà Vinh |

### Miền Trung
| Code | Tên |
|------|-----|
| TH | Thừa Thiên Huế |
| PY | Phú Yên |
| DNG | Đà Nẵng |
| KH | Khánh Hòa |
| BDI | Bình Định |
| QT | Quảng Trị |
| QNM | Quảng Nam |
| DLK | Đắk Lắk |
| QNG | Quảng Ngãi |
| DNO | Đắk Nông |
| GL | Gia Lai |
| NT | Ninh Thuận |
| QBI | Quảng Bình |
| KT | Kon Tum |

### Miền Bắc
| Code | Tên |
|------|-----|
| HN | Hà Nội |
