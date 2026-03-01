# API Spec — Xổ Số Truyền Thống

## Endpoint

```
GET /api/v1/lottery/traditional
```

## Query Parameters

| Param | Type | Required | Default | Mô tả |
|---|---|---|---|---|
| `region` | string | ✅ | — | `mien-nam`, `mien-trung`, `mien-bac` |
| `date` | string | ❌ | Hôm nay | Format: `YYYY-MM-DD` |
| `province` | string | ❌ | Tất cả | Mã tỉnh: `VL`, `BD`, `TV`... (lọc 1 đài) |

## Ví dụ Request

```bash
# Miền Nam hôm nay
GET /api/v1/lottery/traditional?region=mien-nam

# Miền Nam ngày cụ thể
GET /api/v1/lottery/traditional?region=mien-nam&date=2026-02-27

# Chỉ đài Vĩnh Long
GET /api/v1/lottery/traditional?region=mien-nam&date=2026-02-27&province=VL
```

## Response (200 OK)

```json
{
  "status": "ok",
  "data": {
    "date": "2026-02-27",
    "region": "mien-nam",
    "region_name": "Miền Nam",
    "draw_time": "16:15",
    "results": [
      {
        "province": "Vĩnh Long",
        "province_code": "VL",
        "prizes": {
          "giai_db": ["123456"],
          "giai_1":  ["12345"],
          "giai_2":  ["12345"],
          "giai_3":  ["12345", "67890"],
          "giai_4":  ["12345", "67890", "11111", "22222", "33333", "44444", "55555"],
          "giai_5":  ["1234"],
          "giai_6":  ["1234", "5678", "9012"],
          "giai_7":  ["123"],
          "giai_8":  ["12"]
        }
      },
      {
        "province": "Bình Dương",
        "province_code": "BD",
        "prizes": {
          "giai_db": ["654321"],
          "giai_1":  ["54321"],
          "giai_2":  ["54321"],
          "giai_3":  ["54321", "09876"],
          "giai_4":  ["54321", "09876", "11111", "22222", "33333", "44444", "55555"],
          "giai_5":  ["4321"],
          "giai_6":  ["4321", "8765", "2109"],
          "giai_7":  ["321"],
          "giai_8":  ["21"]
        }
      },
      {
        "province": "Trà Vinh",
        "province_code": "TV",
        "prizes": { "..." : "..." }
      }
    ]
  },
  "meta": {
    "cached": false,
    "fetched_at": "2026-02-27T16:30:00+07:00"
  }
}
```

## Cấu trúc giải thưởng

### Miền Nam & Miền Trung (giống nhau)

| Giải | Key | Số lượng số/đài | Độ dài số |
|---|---|---|---|
| Giải Đặc biệt | `giai_db` | 1 | 6 chữ số |
| Giải Nhất | `giai_1` | 1 | 5 chữ số |
| Giải Nhì | `giai_2` | 1 | 5 chữ số |
| Giải Ba | `giai_3` | 2 | 5 chữ số |
| Giải Tư | `giai_4` | 7 | 5 chữ số |
| Giải Năm | `giai_5` | 1 | 4 chữ số |
| Giải Sáu | `giai_6` | 3 | 4 chữ số |
| Giải Bảy | `giai_7` | 1 | 3 chữ số |
| Giải Tám | `giai_8` | 1 | 2 chữ số |
| **Tổng** | | **18 số/đài** | |

### Miền Bắc

| Giải | Key | Số lượng số | Độ dài số |
|---|---|---|---|
| Giải Đặc biệt | `giai_db` | 1 | 6 chữ số |
| Giải Nhất | `giai_1` | 1 | 5 chữ số |
| Giải Nhì | `giai_2` | 2 | 5 chữ số |
| Giải Ba | `giai_3` | 6 | 5 chữ số |
| Giải Tư | `giai_4` | 4 | 4 chữ số |
| Giải Năm | `giai_5` | 6 | 4 chữ số |
| Giải Sáu | `giai_6` | 3 | 3 chữ số |
| Giải Bảy | `giai_7` | 4 | 2 chữ số |
| **Tổng** | | **27 số/đài** | |

## Lịch quay — Miền Nam

| Thứ | Các đài |
|---|---|
| Thứ 2 | TP.HCM (`HCM`), Đồng Tháp (`DT`), Cà Mau (`CM`) |
| Thứ 3 | Bến Tre (`BT`), Vũng Tàu (`VT`), Bạc Liêu (`BL`) |
| Thứ 4 | Đồng Nai (`DN`), Cần Thơ (`CT`), Sóc Trăng (`ST`) |
| Thứ 5 | Tây Ninh (`TN`), An Giang (`AG`), Bình Thuận (`BTH`) |
| Thứ 6 | Vĩnh Long (`VL`), Bình Dương (`BD`), Trà Vinh (`TV`) |
| Thứ 7 | TP.HCM (`HCM`), Long An (`LA`), Bình Phước (`BP`), Hậu Giang (`HG`) |
| Chủ nhật | Tiền Giang (`TG`), Kiên Giang (`KG`), Đà Lạt (`DL`) |

## Lịch quay — Miền Trung

| Thứ | Các đài |
|---|---|
| Thứ 2 | Thừa Thiên Huế (`TTH`), Phú Yên (`PY`) |
| Thứ 3 | Đắk Lắk (`DLK`), Quảng Nam (`QNM`) |
| Thứ 4 | Đà Nẵng (`DNG`), Khánh Hòa (`KH`) |
| Thứ 5 | Bình Định (`BDI`), Quảng Trị (`QT`), Quảng Bình (`QB`) |
| Thứ 6 | Gia Lai (`GL`), Ninh Thuận (`NT`) |
| Thứ 7 | Đà Nẵng (`DNG`), Quảng Ngãi (`QNG`), Đắk Nông (`DNO`) |
| Chủ nhật | Khánh Hòa (`KH`), Kon Tum (`KT`) |

## Lịch quay — Miền Bắc

Hàng ngày, 1 đài duy nhất (Hà Nội), quay lúc 18:15.

## Error Responses

### 400 Bad Request
```json
{
  "status": "error",
  "error": {
    "code": "INVALID_REGION",
    "message": "Region không hợp lệ. Chấp nhận: mien-nam, mien-trung, mien-bac"
  }
}
```

### 404 Not Found
```json
{
  "status": "error",
  "error": {
    "code": "NO_RESULTS",
    "message": "Không có kết quả cho ngày 2026-02-30"
  }
}
```

### 503 Service Unavailable
```json
{
  "status": "error",
  "error": {
    "code": "SOURCE_UNAVAILABLE",
    "message": "Không thể lấy dữ liệu từ nguồn. Vui lòng thử lại sau."
  }
}
```

## Nguồn Scrape

### URL Pattern — xoso.com.vn

| Miền | URL |
|---|---|
| Miền Nam | `https://xoso.com.vn/xsmn-{DD}-{MM}-{YYYY}.html` |
| Miền Trung | `https://xoso.com.vn/xsmt-{DD}-{MM}-{YYYY}.html` |
| Miền Bắc | `https://xoso.com.vn/xsmb-{DD}-{MM}-{YYYY}.html` |

### Parse Strategy

1. HTTP GET với User-Agent browser
2. Parse HTML bằng goquery
3. Tìm tên tỉnh: `th.prize-col3 h3 a` hoặc `th.prize-col4 h3 a`
4. Tìm số: attribute `data-loto` trên các `<td>`
5. Map row label (`ĐB`, `1`, `2`...) → prize key (`giai_db`, `giai_1`...)
6. Split theo cột `<td>` cho mỗi tỉnh
