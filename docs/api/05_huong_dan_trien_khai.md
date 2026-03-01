# Hướng dẫn triển khai Go API Service

## 1. Cấu trúc project đề xuất

```
lottolive-api/
├── cmd/
│   └── server/
│       └── main.go              # Entry point
├── internal/
│   ├── handler/
│   │   ├── health.go            # GET /health
│   │   ├── traditional.go       # GET /lottery/traditional
│   │   ├── vietlot.go           # GET /lottery/vietlot
│   │   ├── latest.go            # GET /lottery/latest
│   │   └── schedule.go          # GET /lottery/schedule
│   ├── scraper/
│   │   ├── xoso.go              # Scrape xoso.com.vn (truyền thống)
│   │   ├── vietlott.go          # Scrape vietlott.vn (Vietlot)
│   │   └── scraper.go           # Interface chung
│   ├── model/
│   │   ├── traditional.go       # Struct kết quả truyền thống
│   │   ├── vietlot.go           # Struct kết quả Vietlot
│   │   └── response.go          # Struct response chung
│   ├── cache/
│   │   └── memory.go            # In-memory cache (sync.Map + TTL)
│   └── schedule/
│       └── schedule.go          # Lịch quay tĩnh
├── config/
│   └── config.go                # Env config
├── Dockerfile
├── docker-compose.yml
├── go.mod
└── go.sum
```

## 2. Dependencies Go

```
go get github.com/PuerkitoBio/goquery   # HTML parser
go get github.com/go-chi/chi/v5         # HTTP router (nhẹ)
go get github.com/go-chi/chi/v5/middleware
```

## 3. Flow xử lý request

```
Request → Router → Handler → Check Cache
                                  │
                          ┌───────┴───────┐
                          │ Cache HIT     │ Cache MISS
                          │ Return cached │ Scrape → Parse → Cache → Return
                          └───────────────┘
```

## 4. Scrape Strategy

### 4.1 xoso.com.vn (Truyền thống)

```go
// URL pattern
url := fmt.Sprintf("https://xoso.com.vn/%s-%s.html", prefix, dateStr)
// prefix: "xsmn", "xsmt", "xsmb"
// dateStr: "27-02-2026"

// Parse steps:
// 1. HTTP GET với User-Agent
// 2. goquery: doc.Find("th.prize-col3 h3 a, th.prize-col4 h3 a") → tên tỉnh
// 3. goquery: doc.Find("tr") → mỗi row là 1 giải
// 4. Trong mỗi row: Find("[data-loto]") → số trúng
// 5. Split theo cột <td> cho mỗi tỉnh
```

### 4.2 vietlott.vn (Vietlot)

```go
// Kiểm tra Network tab trước — vietlott.vn có thể dùng AJAX
// Nếu có XHR endpoint → gọi trực tiếp (ưu tiên)
// Nếu không → parse HTML

// Mega 6/45
url := "https://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/mega-645"

// Power 6/55
url := "https://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/power-655"
```

## 5. Cache Implementation

```go
type CacheEntry struct {
    Data      interface{}
    ExpiresAt time.Time
}

type Cache struct {
    store sync.Map
}

func (c *Cache) Get(key string) (interface{}, bool) {
    val, ok := c.store.Load(key)
    if !ok { return nil, false }
    entry := val.(CacheEntry)
    if time.Now().After(entry.ExpiresAt) {
        c.store.Delete(key)
        return nil, false
    }
    return entry.Data, true
}

func (c *Cache) Set(key string, data interface{}, ttl time.Duration) {
    c.store.Store(key, CacheEntry{Data: data, ExpiresAt: time.Now().Add(ttl)})
}
```

### Cache Key Pattern

```
traditional:mien-nam:2026-02-27
traditional:mien-nam:2026-02-27:VL
vietlot:mega645:2026-02-27
vietlot:keno:2026-02-27:256
latest
schedule:2026-02-27
```

## 6. Environment Variables

```env
PORT=8080
ENV=production
LOG_LEVEL=info

# Scrape config
SCRAPE_TIMEOUT=10s
SCRAPE_USER_AGENT=Mozilla/5.0 (compatible; LottoLiveBot/1.0)

# Cache TTL
CACHE_TTL_LIVE=300          # 5 phút (chờ kết quả)
CACHE_TTL_DONE=3600         # 1 giờ (đã có kết quả)
CACHE_TTL_HISTORY=86400     # 24 giờ (ngày trước)
CACHE_TTL_KENO=120          # 2 phút
CACHE_TTL_SCHEDULE=604800   # 7 ngày

# Rate limit
RATE_LIMIT_PUBLIC=30         # req/phút
RATE_LIMIT_APP=100           # req/phút
API_KEY=your-secret-key
```

## 7. Docker

```dockerfile
FROM golang:1.22-alpine AS builder
WORKDIR /app
COPY go.mod go.sum ./
RUN go mod download
COPY . .
RUN CGO_ENABLED=0 go build -o server ./cmd/server

FROM alpine:3.19
RUN apk --no-cache add ca-certificates tzdata
ENV TZ=Asia/Ho_Chi_Minh
COPY --from=builder /app/server /server
EXPOSE 8080
CMD ["/server"]
```

```yaml
# docker-compose.yml
services:
  api:
    build: .
    ports:
      - "8080:8080"
    environment:
      - PORT=8080
      - ENV=production
    restart: unless-stopped
```

## 8. Checklist triển khai

- [ ] Khởi tạo Go project (`go mod init`)
- [ ] Implement scraper xoso.com.vn (truyền thống 3 miền)
- [ ] Implement scraper vietlott.vn (Mega, Power, Max3D, Keno)
- [ ] Implement in-memory cache
- [ ] Implement handlers (5 endpoints)
- [ ] Thêm rate limiting middleware
- [ ] Thêm CORS middleware (cho app Flutter)
- [ ] Test với curl
- [ ] Dockerize
- [ ] Deploy lên VPS / Cloud Run
- [ ] Cập nhật Flutter app để gọi API mới
