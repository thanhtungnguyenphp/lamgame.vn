# Job Options API Testing Guide

Hướng dẫn chi tiết về cách test và sử dụng Job Options API với Postman và các công cụ khác.

## 📋 Mục Lục

1. [Import Postman Collection](#import-postman-collection)
2. [Environment Setup](#environment-setup)
3. [Testing Workflow](#testing-workflow)
4. [API Response Examples](#api-response-examples)
5. [Troubleshooting](#troubleshooting)
6. [Performance Testing](#performance-testing)

## 🚀 Import Postman Collection

### Bước 1: Import Collection
1. Mở Postman
2. Click **Import** button
3. Chọn file: `docs/postman/Job_Options_API.postman_collection.json`
4. Click **Import**

### Bước 2: Import Environment
1. Trong Postman, click biểu tượng ⚙️ (Settings) 
2. Chọn **Manage Environments**
3. Click **Import**
4. Chọn file: `docs/postman/Job_API_Environment.postman_environment.json`
5. Click **Import**

### Bước 3: Set Environment
1. Trong dropdown environment (góc trên bên phải)
2. Chọn **Job API - Local Environment**
3. Đảm bảo `base_url` đúng với environment của bạn

## ⚙️ Environment Setup

### Environment Variables

| Variable | Local | Staging | Production | Description |
|----------|-------|---------|-----------|-------------|
| `base_url` | `https://lamgame.localhost` | `https://staging.lamgame.vn` | `https://lamgame.vn` | Base API URL |
| `api_version` | `1.0` | `1.0` | `1.0` | API Version |
| `timeout` | `5000` | `8000` | `10000` | Request timeout (ms) |
| `test_search_term` | `php` | `php` | `php` | Default search term |
| `test_limit` | `10` | `10` | `10` | Default result limit |

### Cập Nhật Base URL Theo Environment

**Local Development:**
```
https://lamgame.localhost
http://localhost:8000
```

**Docker Setup:**
```
http://localhost (nếu dùng port 80)
https://lamgame.localhost (nếu có SSL)
```

**Staging:**
```
https://staging.lamgame.vn
```

**Production:**
```
https://lamgame.vn
```

## 🧪 Testing Workflow

### 1. Smoke Tests (Kiểm tra cơ bản)

Chạy các endpoint cơ bản để đảm bảo API hoạt động:

1. **Get All Filter Options** - Test endpoint tổng hợp
2. **Get Job Form Data** - Test endpoint cho form
3. **Get Locations** - Test Vietnamese locations
4. **Get Skills** - Test skills data

### 2. Functional Tests (Kiểm tra chức năng)

#### Test Search Functionality:
```bash
# Test search locations
GET /api/jobs/options/locations?search=HCM&limit=5

# Test search skills  
GET /api/jobs/options/skills?search=php&limit=10

# Test search companies
GET /api/jobs/options/companies?search=FPT&limit=5

# Test search benefits
GET /api/jobs/options/benefits?search=bảo hiểm&limit=5
```

#### Test Category Filtering:
```bash
# Test IT skills
GET /api/jobs/options/skills?category=IT&limit=20

# Test Marketing skills
GET /api/jobs/options/skills?category=Marketing&limit=20

# Test Design skills
GET /api/jobs/options/skills?category=Design&limit=20
```

#### Test Multi-Search:
```bash
# Search across multiple types
GET /api/jobs/options/search?query=php&types[]=skills&types[]=companies&limit=10
```

### 3. Edge Cases Tests

#### Test Limits:
```bash
# Test maximum limit
GET /api/jobs/options/locations?limit=100

# Test over limit (should cap at 100)
GET /api/jobs/options/locations?limit=200

# Test minimum search length
GET /api/jobs/options/search?query=a  # Should fail (min 2 chars)
```

#### Test Empty Results:
```bash
# Test non-existent search
GET /api/jobs/options/skills?search=nonexistentskill123

# Test invalid category  
GET /api/jobs/options/skills?category=InvalidCategory
```

### 4. Performance Tests

#### Response Time Tests:
- Tất cả endpoint phải respond < 5000ms
- Endpoint có cache phải respond < 500ms lần 2

#### Load Tests:
```bash
# Test rate limiting (should allow 120 req/min)
# Gửi 120 requests trong 1 phút -> OK
# Gửi request 121 -> Should be rate limited
```

## 📊 API Response Examples

### Successful Response Format:
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": [...],
  "total": 25,
  "meta": {
    "generated_at": "2025-10-13T09:15:00.000Z",
    "cache_ttl": 3600,
    "version": "1.0"
  }
}
```

### Error Response Format:
```json
{
  "success": false,
  "message": "Validation failed",
  "error": "The query field is required when search is performed"
}
```

### Detailed Response Examples:

#### 1. Get All Filter Options Response:
```json
{
  "success": true,
  "message": "Filter options retrieved successfully",
  "data": {
    "job_types": [
      {
        "id": 1,
        "value": "Toàn thời gian",
        "label": "Toàn thời gian",
        "count": null,
        "sort_order": 1,
        "slug": null,
        "metadata": null
      },
      {
        "id": 2,
        "value": "Bán thời gian",
        "label": "Bán thời gian",
        "count": null,
        "sort_order": 2,
        "slug": null,
        "metadata": null
      }
    ],
    "experience_levels": [
      {
        "id": 5,
        "value": "Fresher",
        "label": "Fresher",
        "count": null,
        "sort_order": 1,
        "slug": null,
        "metadata": null
      }
    ],
    "locations": [
      {
        "id": null,
        "value": "TP.HCM",
        "label": "TP.HCM",
        "count": null,
        "sort_order": null,
        "slug": null,
        "metadata": null
      }
    ]
  }
}
```

#### 2. Search Options Response:
```json
{
  "success": true,
  "message": "Search completed successfully",
  "data": {
    "skills": [
      {
        "id": null,
        "value": "PHP",
        "label": "PHP",
        "count": 85,
        "sort_order": null,
        "slug": null,
        "metadata": null
      }
    ],
    "companies": [
      {
        "id": null,
        "value": "PHP Company Ltd",
        "label": "PHP Company Ltd",
        "count": 3,
        "sort_order": null,
        "slug": null,
        "metadata": null
      }
    ]
  },
  "query": "php"
}
```

## 🛠️ Troubleshooting

### Common Issues:

#### 1. Connection Errors
**Problem:** `Error: connect ECONNREFUSED`
**Solutions:**
- Kiểm tra server có chạy không
- Kiểm tra `base_url` trong environment
- Kiểm tra Docker containers: `docker-compose ps`

#### 2. 404 Not Found
**Problem:** `404 Not Found` cho API endpoints
**Solutions:**
```bash
# Clear route cache
php artisan route:clear

# Check routes exist
php artisan route:list | grep "api.jobs.options"

# Restart server
docker-compose restart php nginx
```

#### 3. 500 Internal Server Error
**Problem:** Server error response
**Solutions:**
- Check Laravel logs: `storage/logs/laravel.log`
- Check database connection
- Verify JobFilterService dependencies
- Test with Docker: `docker-compose exec php php artisan tinker`

#### 4. Empty Responses
**Problem:** API trả về data rỗng
**Solutions:**
- Kiểm tra database có job data không
- Verify job category setup
- Check attribute options exist
- Test with database query directly

### Debug Commands:

```bash
# Check if routes are registered
docker-compose exec php php artisan route:list | grep options

# Test service directly in tinker
docker-compose exec php php artisan tinker
>>> app(\App\Services\JobFilterService::class)->getLocations()

# Check database
docker-compose exec php php artisan tinker  
>>> \Webkul\Category\Models\Category::where('slug', 'viec-lam')->first()
```

### Validation Error Examples:

#### Search endpoint validation:
```bash
# Missing query parameter
GET /api/jobs/options/search
# Response: {"success": false, "message": "Validation failed", "errors": {"query": ["The query field is required."]}}

# Query too short
GET /api/jobs/options/search?query=a
# Response: {"success": false, "message": "Validation failed", "errors": {"query": ["The query must be at least 2 characters."]}}

# Invalid type
GET /api/jobs/options/search?query=test&types[]=invalid
# Response: {"success": false, "message": "Validation failed", "errors": {"types.0": ["The selected types.0 is invalid."]}}
```

## 🚀 Performance Testing

### Using Artillery.js for Load Testing:

#### Install Artillery:
```bash
npm install -g artillery
```

#### Create test config `artillery-test.yml`:
```yaml
config:
  target: 'https://lamgame.localhost'
  phases:
    - duration: 60
      arrivalRate: 2  # 2 requests per second = 120/min (at rate limit)
  http:
    timeout: 10

scenarios:
  - name: "Test Job Options API"
    requests:
      - get:
          url: "/api/jobs/options/filter-options"
          headers:
            Accept: "application/json"
      - get:
          url: "/api/jobs/options/locations?limit=20"
      - get:
          url: "/api/jobs/options/skills?category=IT&limit=15"
      - get:
          url: "/api/jobs/options/search?query=php&types[]=skills&limit=10"
```

#### Run load test:
```bash
artillery run artillery-test.yml
```

### Expected Performance Metrics:

| Endpoint | First Call | Cached Call | Rate Limit | 
|----------|------------|-------------|------------|
| Filter Options | < 2s | < 500ms | 120/min |
| Form Data | < 3s | < 800ms | 120/min |
| Search | < 1s | < 300ms | 120/min |
| Individual Options | < 1s | < 200ms | 120/min |

## 📝 Test Automation Scripts

### Postman Collection Runner:
1. Trong Postman, chọn Collection "Job Options API"
2. Click "Run Collection" 
3. Chọn environment và iterations
4. Click "Run Job Options API"

### Newman CLI (Command Line):
```bash
# Install Newman
npm install -g newman

# Run collection
newman run docs/postman/Job_Options_API.postman_collection.json \
  -e docs/postman/Job_API_Environment.postman_environment.json \
  --reporters html \
  --reporter-html-export results.html
```

### Continuous Integration Script:
```bash
#!/bin/bash
# ci-test-api.sh

echo "🚀 Starting API Tests..."

# 1. Health check
echo "📡 Testing API health..."
response=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/api/jobs/options/filter-options")
if [ $response -ne 200 ]; then
  echo "❌ API health check failed: HTTP $response"
  exit 1
fi

# 2. Run Newman tests
echo "🧪 Running Postman tests..."
newman run docs/postman/Job_Options_API.postman_collection.json \
  -e docs/postman/Job_API_Environment.postman_environment.json \
  --bail

echo "✅ All API tests passed!"
```

Với các file và hướng dẫn này, bạn có thể dễ dàng test tất cả các API endpoints đã tạo và đảm bảo chúng hoạt động đúng như mong muốn.