# YÊU CẦU BACKEND - LOTTO LIVE APP

> Ngày: 15/03/2026
> Base URL hiện tại: http://lamgame.vn/api/v1
> App: Flutter (Android/iOS)

---

## 1. PUSH NOTIFICATION KHI CÓ KQXS [ƯU TIÊN CAO]

### Mô tả
Khi có kết quả xổ số mới, server gửi FCM push notification đến các topic tương ứng.
App đã subscribe sẵn các topic phía client.

### FCM Topics đã subscribe

| Topic | Khi nào gửi |
|-------|-------------|
| `kqxs_mien_nam` | Có KQXS Miền Nam (~16:35 hàng ngày) |
| `kqxs_mien_trung` | Có KQXS Miền Trung (~17:35 hàng ngày) |
| `kqxs_mien_bac` | Có KQXS Miền Bắc (~18:35 hàng ngày) |
| `vietlot` | Có KQ Vietlot bất kỳ game nào |

### Payload mẫu

```json
{
  "to": "/topics/kqxs_mien_nam",
  "notification": {
    "title": "KQXS Miền Nam - 15/03/2026",
    "body": "TP.HCM: ĐB 123456 | Đồng Nai: ĐB 789012"
  },
  "data": {
    "type": "kqxs",
    "region": "mien-nam",
    "date": "2026-03-15"
  }
}
```

### Yêu cầu
- Gửi ngay khi crawl xong KQXS (không delay)
- `notification.body` chứa giải ĐB của các đài trong ngày
- `data.region` và `data.date` để app navigate đúng màn hình

---

## 2. API DÒ SỐ TỰ ĐỘNG [ƯU TIÊN CAO]

### Mô tả
App hiện dò số phía client (fetch KQ rồi so sánh). Cần API server-side để:
- Dò chính xác hơn (server có đầy đủ dữ liệu)
- Push notification khi trúng giải

### Endpoint mới

```
POST /api/v1/lottery/check
```

### Request

```json
{
  "numbers": ["123456"],
  "region": "mien-nam",
  "date": "2026-03-15",
  "province_code": "HCM"
}
```

| Field | Type | Required | Mô tả |
|-------|------|----------|-------|
| numbers | string[] | ✅ | Danh sách số cần dò (2-6 chữ số) |
| region | string | ✅ | mien-nam, mien-trung, mien-bac |
| date | string | ✅ | Ngày xổ YYYY-MM-DD |
| province_code | string | ❌ | Mã đài (HCM, DN...). Null = tất cả đài |

### Response

```json
{
  "status": "ok",
  "data": {
    "date": "2026-03-15",
    "region": "mien-nam",
    "matches": [
      {
        "number": "123456",
        "province": "TP. Hồ Chí Minh",
        "province_code": "HCM",
        "prize": "giai_7",
        "prize_name": "Giải 7",
        "matched_number": "56",
        "full_prize_number": "123456"
      }
    ],
    "total_matches": 1
  }
}
```

---

## 3. ĐĂNG KÝ VÉ SỐ ĐỂ TỰ ĐỘNG DÒ + PUSH [ƯU TIÊN CAO]

### Mô tả
User lưu vé số trên app. Server cần biết để tự động dò khi có KQ và push thông báo trúng/trượt.

### Endpoint mới

```
POST /api/v1/user/tickets
```

### Request

```json
{
  "fcm_token": "dK8x...",
  "numbers": ["123456"],
  "region": "mien-nam",
  "province_code": "HCM",
  "draw_date": "2026-03-15"
}
```

### Response

```json
{
  "status": "ok",
  "data": {
    "ticket_id": "t_abc123",
    "status": "pending"
  }
}
```

### Push khi có kết quả

```json
{
  "to": "<fcm_token>",
  "notification": {
    "title": "🎉 Vé 123456 trúng Giải 7!",
    "body": "TP.HCM - 15/03/2026. Tap để xem chi tiết."
  },
  "data": {
    "type": "ticket_result",
    "ticket_id": "t_abc123",
    "status": "won",
    "matches": [{"prize": "giai_7", "number": "123456"}]
  }
}
```

Nếu không trúng:

```json
{
  "to": "<fcm_token>",
  "notification": {
    "title": "Kết quả dò vé 123456",
    "body": "Không trúng giải. TP.HCM - 15/03/2026"
  },
  "data": {
    "type": "ticket_result",
    "ticket_id": "t_abc123",
    "status": "lost"
  }
}
```

---

## 4. API THỐNG KÊ NÂNG CAO [ƯU TIÊN TRUNG BÌNH]

### Mô tả
App cần dữ liệu thống kê để hiển thị tần suất lô, cầu lô, bạch thủ.

### Endpoint mới

```
GET /api/v1/lottery/statistics
```

### Params

| Param | Type | Required | Mô tả |
|-------|------|----------|-------|
| region | string | ✅ | mien-nam, mien-trung, mien-bac |
| province_code | string | ❌ | Mã đài cụ thể |
| days | int | ❌ | Số ngày thống kê (default: 30) |
| type | string | ❌ | frequency, streak, prediction (default: all) |

### Response

```json
{
  "status": "ok",
  "data": {
    "region": "mien-nam",
    "days": 30,
    "frequency": {
      "top_pairs": [
        {"number": "36", "count": 15, "last_seen": "2026-03-14"},
        {"number": "82", "count": 14, "last_seen": "2026-03-15"}
      ],
      "cold_pairs": [
        {"number": "03", "count": 1, "last_seen": "2026-02-20"}
      ]
    },
    "streaks": {
      "current": [
        {"number": "36", "consecutive_days": 3, "province": "HCM"}
      ],
      "longest": [
        {"number": "82", "consecutive_days": 5, "province": "DN", "from": "2026-03-01", "to": "2026-03-05"}
      ]
    },
    "head_tail": {
      "heads": {"0": 12, "1": 15, "2": 8, "3": 18, "4": 10, "5": 14, "6": 9, "7": 16, "8": 11, "9": 13},
      "tails": {"0": 10, "1": 14, "2": 12, "3": 9, "4": 16, "5": 11, "6": 15, "7": 8, "8": 13, "9": 18}
    }
  }
}
```

---

## 5. ĐỒNG BỘ DỮ LIỆU USER [ƯU TIÊN TRUNG BÌNH]

### Mô tả
Backup vé số lên cloud để không mất khi đổi máy. Dùng Firebase UID làm key.

### Endpoint mới

```
PUT /api/v1/user/sync
Authorization: Bearer <firebase_id_token>
```

### Request

```json
{
  "tickets": [
    {
      "id": "1710489600000",
      "numbers": ["123456"],
      "province": "TP. Hồ Chí Minh",
      "province_code": "HCM",
      "region": "mien-nam",
      "draw_date": "2026-03-15",
      "status": "pending",
      "matched_prizes": [],
      "created_at": "2026-03-15T10:00:00+07:00"
    }
  ],
  "last_sync": "2026-03-15T10:00:00+07:00"
}
```

### Response

```json
{
  "status": "ok",
  "data": {
    "synced": 5,
    "server_tickets": [],
    "last_sync": "2026-03-15T14:30:00+07:00"
  }
}
```

`server_tickets` chứa vé từ server mà client chưa có (trường hợp đăng nhập trên máy mới).

---

## 6. TÓM TẮT ƯU TIÊN

| # | API | Ưu tiên | Phụ thuộc |
|---|-----|---------|-----------|
| 1 | FCM Push khi có KQXS | 🔴 Cao | Cần FCM server key |
| 2 | POST /lottery/check | 🔴 Cao | Không |
| 3 | POST /user/tickets + Push trúng/trượt | 🔴 Cao | #1 |
| 4 | GET /lottery/statistics | 🟡 Trung bình | Không |
| 5 | PUT /user/sync | 🟡 Trung bình | Firebase Auth verify |

### Lưu ý kỹ thuật
- FCM Server Key cần config trong backend (Firebase Console → Project Settings → Cloud Messaging)
- Hoặc dùng Firebase Admin SDK để gửi push
- API sync cần verify Firebase ID Token (Authorization header)
- Response format giữ nguyên chuẩn hiện tại: `{"status": "ok", "data": {...}, "meta": {...}}`
- Base URL: `http://lamgame.vn/api/v1`
