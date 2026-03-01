# API Spec — Vietlot

## Endpoint

```
GET /api/v1/lottery/vietlot
```

## Query Parameters

| Param | Type | Required | Default | Mô tả |
|---|---|---|---|---|
| `game` | string | ✅ | — | `mega645`, `power655`, `max3d`, `max3d_pro`, `keno` |
| `date` | string | ❌ | Kỳ gần nhất | Format: `YYYY-MM-DD` |
| `period` | string | ❌ | — | Kỳ quay (dùng cho Keno): `latest` hoặc số kỳ |

---

## Game 1: Mega 6/45

### Thông tin
- Lịch quay: **Thứ 4, Thứ 6, Chủ nhật** — 18:00
- Chọn 6 số từ 1–45
- Jackpot tích lũy, khởi điểm 12 tỷ VNĐ

### Request
```bash
GET /api/v1/lottery/vietlot?game=mega645
GET /api/v1/lottery/vietlot?game=mega645&date=2026-02-27
```

### Response
```json
{
  "status": "ok",
  "data": {
    "game": "mega645",
    "game_name": "Mega 6/45",
    "date": "2026-02-27",
    "draw_id": "01085",
    "draw_time": "18:00",
    "numbers": [3, 12, 25, 33, 41, 45],
    "jackpot": {
      "value": 15234567890,
      "value_display": "15.2 tỷ",
      "currency": "VND",
      "winners": 0
    },
    "prizes": [
      {"name": "Jackpot",  "match": "6 số",   "value": 15234567890, "winners": 0},
      {"name": "Giải Nhất", "match": "5 số",   "value": 10000000,    "winners": 5},
      {"name": "Giải Nhì",  "match": "4 số",   "value": 300000,      "winners": 142},
      {"name": "Giải Ba",   "match": "3 số",   "value": 30000,       "winners": 2891}
    ]
  },
  "meta": {
    "cached": true,
    "fetched_at": "2026-02-27T18:05:00+07:00"
  }
}
```

---

## Game 2: Power 6/55

### Thông tin
- Lịch quay: **Thứ 3, Thứ 5, Thứ 7** — 18:00
- Chọn 6 số từ 1–55 + 1 Power number từ 1–55
- 2 Jackpot tích lũy

### Request
```bash
GET /api/v1/lottery/vietlot?game=power655
GET /api/v1/lottery/vietlot?game=power655&date=2026-02-27
```

### Response
```json
{
  "status": "ok",
  "data": {
    "game": "power655",
    "game_name": "Power 6/55",
    "date": "2026-02-27",
    "draw_id": "01120",
    "draw_time": "18:00",
    "numbers": [5, 11, 22, 38, 44, 55],
    "power_number": 3,
    "jackpot_1": {
      "value": 52000000000,
      "value_display": "52 tỷ",
      "currency": "VND",
      "winners": 0
    },
    "jackpot_2": {
      "value": 5800000000,
      "value_display": "5.8 tỷ",
      "currency": "VND",
      "winners": 0
    },
    "prizes": [
      {"name": "Jackpot 1", "match": "6 số",          "value": 52000000000, "winners": 0},
      {"name": "Jackpot 2", "match": "5 số + Power",   "value": 5800000000,  "winners": 0},
      {"name": "Giải Nhất",  "match": "5 số",           "value": 40000000,    "winners": 3},
      {"name": "Giải Nhì",   "match": "4 số",           "value": 500000,      "winners": 98},
      {"name": "Giải Ba",    "match": "3 số",           "value": 50000,       "winners": 1820}
    ]
  },
  "meta": {
    "cached": false,
    "fetched_at": "2026-02-27T18:03:00+07:00"
  }
}
```

---

## Game 3: Max 3D

### Thông tin
- Lịch quay: **Hàng ngày** — 18:00
- Chọn 2 bộ 3 chữ số (000–999)
- 6 giải thưởng

### Request
```bash
GET /api/v1/lottery/vietlot?game=max3d
GET /api/v1/lottery/vietlot?game=max3d&date=2026-02-27
```

### Response
```json
{
  "status": "ok",
  "data": {
    "game": "max3d",
    "game_name": "Max 3D",
    "date": "2026-02-27",
    "draw_id": "00456",
    "draw_time": "18:00",
    "numbers_a": "385",
    "numbers_b": "712",
    "prizes": [
      {"name": "Giải Đặc biệt", "match": "Trùng 2 bộ số (A+B) đúng vị trí", "value": 1000000000, "winners": 0},
      {"name": "Giải Nhất",      "match": "Trùng 2 bộ số bất kỳ vị trí",     "value": 400000,     "winners": 12},
      {"name": "Giải Nhì",       "match": "Trùng 1 bộ số đúng vị trí",       "value": 210000,     "winners": 85},
      {"name": "Giải Ba",        "match": "Trùng 1 bộ số bất kỳ vị trí",     "value": 100000,     "winners": 170},
      {"name": "Giải Tư",        "match": "Trùng 2 chữ số cuối 1 bộ",        "value": 40000,      "winners": 1200},
      {"name": "Giải Năm",       "match": "Trùng 1 chữ số cuối 1 bộ",        "value": 10000,      "winners": 8500}
    ]
  },
  "meta": {
    "cached": true,
    "fetched_at": "2026-02-27T18:02:00+07:00"
  }
}
```

---

## Game 4: Max 3D Pro

### Thông tin
- Lịch quay: **Thứ 3, Thứ 5, Thứ 7** — 18:00
- Quay ra 18 bộ 3 chữ số (000–999)
- Nhiều giải hơn Max 3D thường

### Request
```bash
GET /api/v1/lottery/vietlot?game=max3d_pro
GET /api/v1/lottery/vietlot?game=max3d_pro&date=2026-02-27
```

### Response
```json
{
  "status": "ok",
  "data": {
    "game": "max3d_pro",
    "game_name": "Max 3D Pro",
    "date": "2026-02-27",
    "draw_id": "00789",
    "draw_time": "18:00",
    "numbers": [
      ["385", "712"],
      ["100", "896"],
      ["633", "383"],
      ["487", "251"],
      ["351", "687"],
      ["154", "048"],
      ["324", "494"],
      ["739", "784"],
      ["526", "253"]
    ],
    "prizes": [
      {"name": "Giải Đặc biệt", "match": "Trùng 2 bộ số cặp 1 đúng vị trí",  "value": 1000000000},
      {"name": "Giải Nhất",      "match": "Trùng 2 bộ số cặp 1 bất kỳ vị trí", "value": 1000000},
      {"name": "Giải Nhì",       "match": "Trùng 2 bộ số cặp 2–3 đúng vị trí", "value": 500000},
      {"name": "Giải Ba",        "match": "Trùng 2 bộ số cặp 2–3 bất kỳ",      "value": 400000},
      {"name": "Giải Tư",        "match": "Trùng 1 bộ số bất kỳ trong 18 bộ",  "value": 100000},
      {"name": "Giải Năm",       "match": "Trùng 2 chữ số cuối 1 bộ",          "value": 40000},
      {"name": "Giải Sáu",       "match": "Trùng 1 chữ số cuối 1 bộ",          "value": 10000}
    ]
  },
  "meta": {
    "cached": true,
    "fetched_at": "2026-02-27T18:04:00+07:00"
  }
}
```

---

## Game 5: Keno

### Thông tin
- Lịch quay: **Hàng ngày**, mỗi **10 phút** từ 06:00 đến 21:55
- Quay 20 số từ 1–80
- ~96 kỳ/ngày

### Request
```bash
# Kỳ mới nhất
GET /api/v1/lottery/vietlot?game=keno&period=latest

# Theo ngày (trả tất cả kỳ trong ngày)
GET /api/v1/lottery/vietlot?game=keno&date=2026-02-27

# Theo kỳ cụ thể
GET /api/v1/lottery/vietlot?game=keno&period=256
```

### Response (1 kỳ)
```json
{
  "status": "ok",
  "data": {
    "game": "keno",
    "game_name": "Keno",
    "date": "2026-02-27",
    "period": "256",
    "draw_time": "16:10",
    "numbers": [2, 5, 8, 11, 15, 19, 22, 28, 33, 37, 41, 44, 48, 52, 55, 60, 65, 70, 74, 80],
    "stats": {
      "total": 856,
      "big_small": "lon",
      "odd_even": "chan",
      "up_down": "tren"
    }
  },
  "meta": {
    "cached": false,
    "fetched_at": "2026-02-27T16:10:30+07:00"
  }
}
```

### Response (nhiều kỳ — theo ngày)
```json
{
  "status": "ok",
  "data": {
    "game": "keno",
    "date": "2026-02-27",
    "total_periods": 96,
    "periods": [
      {"period": "256", "draw_time": "16:10", "numbers": [2, 5, ...], "stats": {...}},
      {"period": "255", "draw_time": "16:00", "numbers": [1, 7, ...], "stats": {...}},
      "..."
    ]
  },
  "meta": {
    "cached": true,
    "fetched_at": "2026-02-27T16:15:00+07:00"
  }
}
```

### Keno Stats

| Field | Giá trị | Mô tả |
|---|---|---|
| `total` | int | Tổng 20 số |
| `big_small` | `"lon"` / `"nho"` | Tổng ≥ 810 = lớn, < 810 = nhỏ |
| `odd_even` | `"le"` / `"chan"` | Số lẻ > 10 = lẻ, ≤ 10 = chẵn |
| `up_down` | `"tren"` / `"duoi"` | Nhiều số > 40 = trên, ngược lại = dưới |

---

## Error Codes

| Code | HTTP | Mô tả |
|---|---|---|
| `INVALID_GAME` | 400 | Game không hợp lệ |
| `INVALID_DATE` | 400 | Ngày không đúng format |
| `INVALID_PERIOD` | 400 | Kỳ Keno không hợp lệ |
| `NO_RESULTS` | 404 | Chưa có kết quả |
| `NO_DRAW_TODAY` | 404 | Game không quay ngày này |
| `SOURCE_UNAVAILABLE` | 503 | Nguồn dữ liệu lỗi |

---

## Lịch quay Vietlot

| Game | T2 | T3 | T4 | T5 | T6 | T7 | CN |
|---|---|---|---|---|---|---|---|
| Mega 6/45 | | | ✅ | | ✅ | | ✅ |
| Power 6/55 | | ✅ | | ✅ | | ✅ | |
| Max 3D | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Max 3D Pro | | ✅ | | ✅ | | ✅ | |
| Keno | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## Nguồn Scrape — vietlott.vn

| Game | URL |
|---|---|
| Mega 6/45 | `https://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/mega-645` |
| Power 6/55 | `https://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/power-655` |
| Max 3D | `https://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/max-3d` |
| Max 3D Pro | `https://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/max-3d-pro` |
| Keno | `https://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/winning-number-702` |

### Lưu ý Scrape vietlott.vn
- Trang dùng AJAX load data → có thể cần gọi API nội bộ thay vì parse HTML
- Kiểm tra Network tab để tìm XHR endpoint trả JSON
- Fallback: dùng headless browser nếu cần render JS
