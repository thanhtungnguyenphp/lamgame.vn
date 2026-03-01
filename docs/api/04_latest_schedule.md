# API Spec — Latest & Schedule

---

## 1. Endpoint: Kết quả mới nhất (tổng hợp)

```
GET /api/v1/lottery/latest
```

### Query Parameters

| Param | Type | Required | Default | Mô tả |
|---|---|---|---|---|
| `include` | string | ❌ | `all` | Filter: `traditional`, `vietlot`, `all` |

### Response (200 OK)

```json
{
  "status": "ok",
  "data": {
    "updated_at": "2026-02-27T18:30:00+07:00",
    "traditional": {
      "mien_nam": {
        "date": "2026-02-27",
        "draw_time": "16:15",
        "provinces": ["Vĩnh Long", "Bình Dương", "Trà Vinh"],
        "results": [
          {
            "province": "Vĩnh Long",
            "province_code": "VL",
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
      "mien_trung": {
        "date": "2026-02-27",
        "draw_time": "17:15",
        "provinces": ["Gia Lai", "Ninh Thuận"],
        "results": [{"...": "..."}]
      },
      "mien_bac": {
        "date": "2026-02-27",
        "draw_time": "18:15",
        "provinces": ["Hà Nội"],
        "results": [{"...": "..."}]
      }
    },
    "vietlot": {
      "mega645": {
        "date": "2026-02-26",
        "draw_id": "01085",
        "numbers": [3, 12, 25, 33, 41, 45],
        "jackpot": {"value": 15234567890, "value_display": "15.2 tỷ", "winners": 0},
        "next_draw": "2026-02-28"
      },
      "power655": {
        "date": "2026-02-27",
        "draw_id": "01120",
        "numbers": [5, 11, 22, 38, 44, 55],
        "power_number": 3,
        "jackpot_1": {"value": 52000000000, "value_display": "52 tỷ", "winners": 0},
        "jackpot_2": {"value": 5800000000, "value_display": "5.8 tỷ", "winners": 0},
        "next_draw": "2026-03-01"
      },
      "max3d": {
        "date": "2026-02-27",
        "draw_id": "00456",
        "numbers_a": "385",
        "numbers_b": "712"
      },
      "keno": {
        "date": "2026-02-27",
        "period": "256",
        "draw_time": "16:10",
        "numbers": [2, 5, 8, 11, 15, 19, 22, 28, 33, 37, 41, 44, 48, 52, 55, 60, 65, 70, 74, 80],
        "next_draw_time": "16:20"
      }
    }
  },
  "meta": {
    "cached": true,
    "fetched_at": "2026-02-27T18:30:00+07:00"
  }
}
```

---

## 2. Endpoint: Lịch quay số

```
GET /api/v1/lottery/schedule
```

### Query Parameters

| Param | Type | Required | Default | Mô tả |
|---|---|---|---|---|
| `date` | string | ❌ | Hôm nay | Format: `YYYY-MM-DD` |
| `type` | string | ❌ | `all` | `traditional`, `vietlot`, `all` |

### Response (200 OK)

```json
{
  "status": "ok",
  "data": {
    "date": "2026-02-27",
    "day_of_week": "friday",
    "day_of_week_vi": "Thứ Sáu",
    "traditional": {
      "mien_nam": {
        "draw_time": "16:15",
        "provinces": [
          {"name": "Vĩnh Long", "code": "VL"},
          {"name": "Bình Dương", "code": "BD"},
          {"name": "Trà Vinh", "code": "TV"}
        ]
      },
      "mien_trung": {
        "draw_time": "17:15",
        "provinces": [
          {"name": "Gia Lai", "code": "GL"},
          {"name": "Ninh Thuận", "code": "NT"}
        ]
      },
      "mien_bac": {
        "draw_time": "18:15",
        "provinces": [
          {"name": "Hà Nội", "code": "HN"}
        ]
      }
    },
    "vietlot": {
      "mega645": {"has_draw": true, "draw_time": "18:00"},
      "power655": {"has_draw": false, "next_draw": "2026-02-28"},
      "max3d": {"has_draw": true, "draw_time": "18:00"},
      "max3d_pro": {"has_draw": false, "next_draw": "2026-02-28"},
      "keno": {"has_draw": true, "draw_times": "06:00–21:55", "interval": "10 phút"}
    }
  },
  "meta": {
    "cached": true,
    "fetched_at": "2026-02-27T00:00:00+07:00"
  }
}
```

---

## 3. Endpoint: Health Check

```
GET /api/v1/health
```

### Response (200 OK)

```json
{
  "status": "ok",
  "version": "1.0.0",
  "uptime": "2d 5h 30m",
  "sources": {
    "xoso_com_vn": "healthy",
    "vietlott_vn": "healthy"
  }
}
```

---

## 4. Mã tỉnh đầy đủ (Province Codes)

### Miền Nam (21 đài)

| Mã | Tên | Mã | Tên |
|---|---|---|---|
| `HCM` | TP. Hồ Chí Minh | `LA` | Long An |
| `DT` | Đồng Tháp | `BP` | Bình Phước |
| `CM` | Cà Mau | `HG` | Hậu Giang |
| `BT` | Bến Tre | `TG` | Tiền Giang |
| `VT` | Vũng Tàu | `KG` | Kiên Giang |
| `BL` | Bạc Liêu | `DL` | Đà Lạt |
| `DN` | Đồng Nai | `VL` | Vĩnh Long |
| `CT` | Cần Thơ | `BD` | Bình Dương |
| `ST` | Sóc Trăng | `TV` | Trà Vinh |
| `TN` | Tây Ninh | | |
| `AG` | An Giang | | |
| `BTH` | Bình Thuận | | |

### Miền Trung (14 đài)

| Mã | Tên | Mã | Tên |
|---|---|---|---|
| `TTH` | Thừa Thiên Huế | `GL` | Gia Lai |
| `PY` | Phú Yên | `NT` | Ninh Thuận |
| `DLK` | Đắk Lắk | `DNG` | Đà Nẵng |
| `QNM` | Quảng Nam | `QNG` | Quảng Ngãi |
| `KH` | Khánh Hòa | `DNO` | Đắk Nông |
| `BDI` | Bình Định | `KT` | Kon Tum |
| `QT` | Quảng Trị | | |
| `QB` | Quảng Bình | | |

### Miền Bắc

| Mã | Tên |
|---|---|
| `HN` | Hà Nội |
