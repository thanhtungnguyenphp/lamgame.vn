# Tài liệu Scrape Xổ Số Truyền Thống

## Tổng quan

Hệ thống scrape kết quả xổ số truyền thống từ `xoso.com.vn`, parse HTML và lưu vào database.

## Kiến trúc

```
xoso.com.vn (HTML)
    ↓ GuzzleHttp
TraditionalScraper::scrape()
    ↓ DOMDocument + DOMXPath
    ↓ parseHtml() → parseMienBac() / parseMienNamTrung()
    ↓
LotteryDraw (upsert)  →  LotteryResult (upsert per province)
    ↓
LotteryService::getTraditional() → Cache → API Response
```

## Nguồn dữ liệu

| Miền | URL Pattern | Ví dụ |
|---|---|---|
| Miền Nam | `xoso.com.vn/xsmn-{DD}-{MM}-{YYYY}.html` | `xsmn-04-03-2026.html` |
| Miền Trung | `xoso.com.vn/xsmt-{DD}-{MM}-{YYYY}.html` | `xsmt-04-03-2026.html` |
| Miền Bắc | `xoso.com.vn/xsmb-{DD}-{MM}-{YYYY}.html` | `xsmb-04-03-2026.html` |

## Cấu trúc HTML thực tế

### Miền Nam & Miền Trung

```html
<table class="table-result table-xsmn">
  <tr>
    <th class="name-prize">G</th>
    <th class="prize-col3"><h3><a>TPHCM</a></h3></th>
    <th class="prize-col3"><h3><a>Đồng Tháp</a></h3></th>
    <th class="prize-col3"><h3><a>Cà Mau</a></h3></th>
  </tr>
  <tr><th>8</th><td>95</td><td>89</td><td>90</td></tr>        <!-- giai_8 -->
  <tr><th>7</th><td>645</td><td>061</td><td>840</td></tr>      <!-- giai_7 -->
  <tr><th>6</th><td>... ... ...</td><td>...</td><td>...</td></tr>  <!-- giai_6 (3 số) -->
  <!-- ... tiếp tục đến giải ĐB -->
  <tr><th>ĐB</th><td>278841</td><td>586568</td><td>792898</td></tr>
</table>
```

- Thứ tự rows: **giải 8 → 7 → 6 → 5 → 4 → 3 → 2 → 1 → ĐB**
- Province headers: `th.prize-col3 > h3 > a` (hoặc `prize-col4` khi 4 đài)
- Mỗi `<td>` chứa số cách nhau bằng whitespace
- Không có attribute `data-loto` hay class `number`

### Miền Bắc

```html
<table class="table-result">
  <tr>
    <th class="name-prize"></th>
    <td class="number-prize">4VB 7VB 10VB...</td>  <!-- loto, bỏ qua -->
  </tr>
  <tr><th>ĐB</th><td>90148</td></tr>     <!-- giai_db -->
  <tr><th>1</th><td>20116</td></tr>       <!-- giai_1 -->
  <tr><th>2</th><td>09827   74465</td></tr>  <!-- giai_2 (2 số) -->
  <!-- ... tiếp tục đến giải 7 -->
  <tr><th>7</th><td>92   73   60   77</td></tr>
</table>
```

- Thứ tự rows: **ĐB → 1 → 2 → 3 → 4 → 5 → 6 → 7** (ngược với MN/MT)
- Không có `prize-col` header (chỉ 1 đài Hà Nội)
- Row đầu tiên có `td.number-prize` → bỏ qua (data loto)

## Province Name Mapping

Tên trên xoso.com.vn có thể khác DB. Hệ thống xử lý bằng:

1. **Exact match** từ `lottery_provinces.name`
2. **Alias map** cho tên viết tắt:
   - `TPHCM` → `HCM`
   - `TP.HCM` → `HCM`
   - `Đắk Lắk` → `DLK`
   - `Đắk Nông` → `DNO`
3. **Fuzzy match** (substring matching) cho các trường hợp còn lại

## Cấu trúc giải thưởng

### Miền Nam & Miền Trung (9 giải/đài, 18 số)

| Key | Giải | Số lượng | Độ dài |
|---|---|---|---|
| `giai_db` | Đặc biệt | 1 | 6 chữ số |
| `giai_1` | Nhất | 1 | 5 |
| `giai_2` | Nhì | 1 | 5 |
| `giai_3` | Ba | 2 | 5 |
| `giai_4` | Tư | 7 | 5 |
| `giai_5` | Năm | 1 | 4 |
| `giai_6` | Sáu | 3 | 4 |
| `giai_7` | Bảy | 1 | 3 |
| `giai_8` | Tám | 1 | 2 |

### Miền Bắc (8 giải, 27 số)

| Key | Giải | Số lượng | Độ dài |
|---|---|---|---|
| `giai_db` | Đặc biệt | 1 | 6 chữ số |
| `giai_1` | Nhất | 1 | 5 |
| `giai_2` | Nhì | 2 | 5 |
| `giai_3` | Ba | 6 | 5 |
| `giai_4` | Tư | 4 | 4 |
| `giai_5` | Năm | 6 | 4 |
| `giai_6` | Sáu | 3 | 3 |
| `giai_7` | Bảy | 4 | 2 |

## Lịch quay số

### Miền Nam (16:15 hàng ngày, 3-4 đài/ngày)

| Thứ | Đài |
|---|---|
| T2 | HCM, DT, CM |
| T3 | BT, VT, BL |
| T4 | DN, CT, ST |
| T5 | TN, AG, BTH |
| T6 | VL, BD, TV |
| T7 | HCM, LA, BP, HG |
| CN | TG, KG, DL |

### Miền Trung (17:15, 2-3 đài/ngày)

| Thứ | Đài |
|---|---|
| T2 | TTH, PY |
| T3 | DLK, QNM |
| T4 | DNG, KH |
| T5 | BDI, QT, QB |
| T6 | GL, NT |
| T7 | DNG, QNG, DNO |
| CN | KH, KT |

### Miền Bắc (18:15, 1 đài duy nhất: Hà Nội)

## Database Schema

```
lottery_draws
├── id, type(traditional/vietlot), game, region, date
├── draw_time, draw_id, period, status, source, scraped_at
└── indexes: [type,date], [game,date], [region,date], [status]

lottery_results
├── id, draw_id(FK), province_id(FK)
├── prize_data(JSON), jackpot_data(JSON), stats_data(JSON)
└── indexes: [draw_id], [province_id]

lottery_provinces
├── id, code, name, region, sort_order
└── 36 records (21 MN + 14 MT + 1 MB)

lottery_scrape_logs
├── id, source, url, status, response_time_ms, error_message
└── Dùng để monitor và debug
```

## Caching

| Loại | TTL | Config key |
|---|---|---|
| Kết quả hôm nay | 300s (5 phút) | `lottery.cache.ttl_live` |
| Kết quả ngày cũ | 86400s (24h) | `lottery.cache.ttl_history` |

Cache key format: `lottery:traditional:{region}:{date}[:province]`
