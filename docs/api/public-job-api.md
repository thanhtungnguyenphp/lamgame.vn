# Public Job API

API này cho phép truy cập công khai danh sách việc làm trên hệ thống LamGame.vn mà không cần authentication.

## Base URL
```
/api/jobs
```

## Authentication
Không yêu cầu authentication cho tất cả endpoints.

## Rate Limiting
- **60 requests/phút** theo IP cho hầu hết endpoints
- **30 requests/phút** cho một số endpoints đặc biệt

---

## 1. Lấy danh sách job công khai

### `GET /api/jobs`

Lấy danh sách tất cả bài tuyển dụng đang hoạt động (status = active).

#### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `search` | string | No | Tìm kiếm theo title, description, company |
| `category` | integer | No | Lọc theo category ID |
| `job_type` | string | No | Lọc theo loại công việc |
| `experience_level` | string | No | Lọc theo cấp độ kinh nghiệm |
| `location` | string | No | Lọc theo địa điểm |
| `salary_min` | integer | No | Lương tối thiểu (triệu VND) |
| `salary_max` | integer | No | Lương tối đa (triệu VND) |
| `company_size` | string | No | Lọc theo quy mô công ty |
| `is_urgent` | boolean | No | Lọc việc gấp (1 = urgent, 0 = normal) |
| `is_featured` | boolean | No | Lọc việc nổi bật (1 = featured, 0 = normal) |
| `sort` | string | No | `latest`, `salary_high`, `salary_low`, `featured`, `urgent` |
| `per_page` | integer | No | Số item/trang (default: 15, max: 50) |

#### Request Example
```bash
curl -X GET "https://lamgame.localhost/api/jobs?search=developer&job_type=full-time&location=Ho Chi Minh&per_page=10" \
  -H "Accept: application/json"
```

#### Response Success (200)
```json
{
  "success": true,
  "message": "Jobs retrieved successfully",
  "data": [
    {
      "id": 123,
      "title": "Senior PHP Developer",
      "slug": "senior-php-developer",
      "short_description": "Senior developer position with Laravel expertise",
      "job_type": "Full-time",
      "experience_level": "senior",
      "salary_range": "20-30 triệu VND",
      "job_location": "Ho Chi Minh City",
      "company_size": "51-200",
      "required_skills": ["PHP", "Laravel", "MySQL"],
      "application_deadline": {
        "raw": "2025-02-15",
        "formatted": "15/02/2025",
        "human": "2 tuần tới"
      },
      "contact_email": "hr@techcorp.com",
      "is_urgent": false,
      "is_featured": true,
      "status": true,
      "company_info": {
        "name": "TechCorp Vietnam",
        "website": "https://techcorp.com",
        "size": "51-200"
      },
      "created_at": "2025-01-15T10:30:00Z",
      "days_remaining": 15,
      "is_expired": false
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 150,
    "last_page": 10,
    "from": 1,
    "to": 15
  },
  "filters": {
    "available_job_types": ["full-time", "part-time", "contract", "freelance"],
    "available_experience_levels": ["entry", "junior", "mid", "senior", "lead"],
    "available_locations": ["Ho Chi Minh City", "Ha Noi", "Da Nang"],
    "salary_range": {
      "min": 5,
      "max": 100
    }
  }
}
```

---

## 2. Lấy chi tiết job công khai

### `GET /api/jobs/{id}`

Lấy thông tin chi tiết của một bài tuyển dụng cụ thể.

#### Request Example
```bash
curl -X GET "https://lamgame.localhost/api/jobs/123" \
  -H "Accept: application/json"
```

#### Response Success (200)
```json
{
  "success": true,
  "message": "Job retrieved successfully",
  "data": {
    "id": 123,
    "title": "Senior PHP Developer",
    "slug": "senior-php-developer",
    "description": "We are looking for an experienced PHP developer to join our growing team...",
    "short_description": "Senior developer position with Laravel expertise",
    "job_type": "Full-time",
    "experience_level": "senior",
    "salary_range": "20-30 triệu VND",
    "job_location": "Ho Chi Minh City",
    "company_size": "51-200",
    "required_skills": ["PHP", "Laravel", "MySQL", "Git"],
    "education_level": "bachelor",
    "english_level": "intermediate",
    "job_benefits": ["health-insurance", "13th-salary", "flexible-hours"],
    "application_deadline": {
      "raw": "2025-02-15",
      "formatted": "15/02/2025",
      "iso": "2025-02-14T17:00:00.000000Z",
      "human": "2 tuần tới"
    },
    "contact_email": "hr@techcorp.com",
    "contact_phone": "0901234567",
    "company_website": "https://techcorp.com",
    "application_method": "email",
    "is_urgent": false,
    "is_featured": true,
    "status": true,
    "categories": [
      {
        "id": 102,
        "name": "Việc Làm",
        "slug": "viec-lam"
      }
    ],
    "company_info": {
      "name": "TechCorp Vietnam",
      "size": "51-200",
      "website": "https://techcorp.com",
      "contact": {
        "email": "hr@techcorp.com",
        "phone": "0901234567"
      }
    },
    "meta": {
      "title": "Senior PHP Developer - TechCorp Vietnam",
      "description": "Join TechCorp Vietnam as a Senior PHP Developer...",
      "keywords": "PHP, Laravel, Developer, Ho Chi Minh"
    },
    "created_at": "2025-01-15T10:30:00Z",
    "updated_at": "2025-01-15T12:45:00Z",
    "days_remaining": 15,
    "is_expired": false,
    "related_jobs": [
      {
        "id": 124,
        "title": "Junior PHP Developer",
        "company_name": "TechCorp Vietnam",
        "salary_range": "10-15 triệu VND"
      }
    ]
  }
}
```

#### Response Error (404)
```json
{
  "success": false,
  "message": "Job not found",
  "error": "The requested job does not exist or is not active"
}
```

---

## 3. Lấy danh mục việc làm

### `GET /api/jobs/categories`

Lấy danh sách các danh mục việc làm có sẵn.

#### Request Example
```bash
curl -X GET "https://lamgame.localhost/api/jobs/categories" \
  -H "Accept: application/json"
```

#### Response Success (200)
```json
{
  "success": true,
  "message": "Categories retrieved successfully",
  "data": [
    {
      "id": 102,
      "name": "Việc Làm",
      "slug": "viec-lam",
      "description": "Tất cả các bài tuyển dụng",
      "job_count": 150,
      "subcategories": [
        {
          "id": 103,
          "name": "IT - Phần mềm",
          "slug": "it-phan-mem",
          "job_count": 85
        },
        {
          "id": 104,
          "name": "Marketing",
          "slug": "marketing",
          "job_count": 25
        }
      ]
    }
  ]
}
```

---

## 4. Lấy thuộc tính job

### `GET /api/jobs/attributes`

Lấy danh sách tất cả thuộc tính có thể dùng để filter jobs.

#### Request Example
```bash
curl -X GET "https://lamgame.localhost/api/jobs/attributes" \
  -H "Accept: application/json"
```

#### Response Success (200)
```json
{
  "success": true,
  "message": "Job attributes retrieved successfully",
  "data": {
    "job_types": [
      {
        "value": "full-time",
        "label": "Full-time",
        "count": 120
      },
      {
        "value": "part-time",
        "label": "Part-time",
        "count": 15
      },
      {
        "value": "contract",
        "label": "Contract",
        "count": 10
      },
      {
        "value": "freelance",
        "label": "Freelance",
        "count": 5
      }
    ],
    "experience_levels": [
      {
        "value": "entry",
        "label": "Entry Level",
        "count": 20
      },
      {
        "value": "junior",
        "label": "Junior",
        "count": 35
      },
      {
        "value": "mid",
        "label": "Mid Level",
        "count": 50
      },
      {
        "value": "senior",
        "label": "Senior",
        "count": 35
      },
      {
        "value": "lead",
        "label": "Lead/Manager",
        "count": 10
      }
    ],
    "locations": [
      {
        "value": "Ho Chi Minh City",
        "label": "TP. Hồ Chí Minh",
        "count": 80
      },
      {
        "value": "Ha Noi",
        "label": "Hà Nội",
        "count": 45
      },
      {
        "value": "Da Nang",
        "label": "Đà Nẵng",
        "count": 15
      },
      {
        "value": "Remote",
        "label": "Remote",
        "count": 10
      }
    ],
    "company_sizes": [
      {
        "value": "1-10",
        "label": "Startup (1-10 người)",
        "count": 25
      },
      {
        "value": "11-50",
        "label": "Công ty nhỏ (11-50 người)",
        "count": 40
      },
      {
        "value": "51-200",
        "label": "Công ty vừa (51-200 người)",
        "count": 50
      },
      {
        "value": "201-500",
        "label": "Công ty lớn (201-500 người)",
        "count": 25
      },
      {
        "value": "500+",
        "label": "Tập đoàn (500+ người)",
        "count": 10
      }
    ],
    "popular_skills": [
      {
        "skill": "PHP",
        "count": 45
      },
      {
        "skill": "Laravel",
        "count": 40
      },
      {
        "skill": "JavaScript",
        "count": 60
      },
      {
        "skill": "React",
        "count": 35
      },
      {
        "skill": "Vue.js",
        "count": 30
      }
    ]
  }
}
```

---

## 5. Tìm kiếm job nâng cao

### `POST /api/jobs/search`

Endpoint tìm kiếm nâng cao với nhiều tiêu chí phức tạp.

#### Request Body
```json
{
  "search_term": "PHP developer",
  "filters": {
    "job_types": ["full-time", "part-time"],
    "experience_levels": ["mid", "senior"],
    "locations": ["Ho Chi Minh City", "Remote"],
    "salary_range": {
      "min": 15,
      "max": 50
    },
    "company_sizes": ["51-200", "201-500"],
    "required_skills": ["PHP", "Laravel"],
    "benefits": ["health-insurance", "remote-work"],
    "posted_within_days": 30,
    "is_urgent": false,
    "is_featured": true
  },
  "sort": "salary_high",
  "per_page": 20
}
```

#### Request Example
```bash
curl -X POST "https://lamgame.localhost/api/jobs/search" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "search_term": "PHP developer",
    "filters": {
      "job_types": ["full-time"],
      "experience_levels": ["senior"],
      "locations": ["Ho Chi Minh City"],
      "salary_range": {
        "min": 20,
        "max": 40
      }
    },
    "sort": "latest",
    "per_page": 10
  }'
```

#### Response Success (200)
Same format as GET /api/jobs response.

---

## 6. Lấy job trending

### `GET /api/jobs/trending`

Lấy danh sách những job hot/trending trong tuần.

#### Query Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `period` | string | No | `week`, `month` (default: `week`) |
| `limit` | integer | No | Số lượng jobs (default: 10, max: 20) |

#### Request Example
```bash
curl -X GET "https://lamgame.localhost/api/jobs/trending?period=week&limit=5" \
  -H "Accept: application/json"
```

#### Response Success (200)
```json
{
  "success": true,
  "message": "Trending jobs retrieved successfully",
  "data": [
    {
      "id": 125,
      "title": "Senior Full-Stack Developer",
      "company_name": "Tech Startup",
      "salary_range": "25-35 triệu VND",
      "job_location": "Ho Chi Minh City",
      "is_urgent": true,
      "is_featured": true,
      "view_count": 1250,
      "application_count": 85,
      "trending_score": 95.5
    }
  ]
}
```

---

## 7. Lấy job tương tự

### `GET /api/jobs/{id}/similar`

Lấy danh sách job tương tự dựa trên job hiện tại.

#### Query Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `limit` | integer | No | Số lượng jobs (default: 5, max: 10) |

#### Request Example
```bash
curl -X GET "https://lamgame.localhost/api/jobs/123/similar?limit=3" \
  -H "Accept: application/json"
```

#### Response Success (200)
```json
{
  "success": true,
  "message": "Similar jobs retrieved successfully",
  "data": [
    {
      "id": 124,
      "title": "PHP Developer",
      "company_name": "Another Tech Company",
      "salary_range": "18-28 triệu VND",
      "job_location": "Ho Chi Minh City",
      "similarity_score": 0.85
    }
  ]
}
```

---

## Error Responses

### Job Not Found (404)
```json
{
  "success": false,
  "message": "Job not found",
  "error": "The requested job does not exist or is not active"
}
```

### Rate Limit Exceeded (429)
```json
{
  "message": "Too Many Attempts."
}
```

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "per_page": ["The per page must be between 1 and 50."],
    "sort": ["The selected sort is invalid."]
  }
}
```

---

## Response Data Format

### Job Object
```json
{
  "id": 123,
  "title": "Job Title",
  "slug": "job-title-slug",
  "description": "Full job description",
  "short_description": "Brief description",
  "job_type": "full-time",
  "experience_level": "senior",
  "salary_range": "20-30 triệu VND",
  "job_location": "Location",
  "company_size": "51-200",
  "required_skills": ["skill1", "skill2"],
  "education_level": "bachelor",
  "english_level": "intermediate",
  "job_benefits": ["benefit1", "benefit2"],
  "application_deadline": {
    "raw": "2025-02-15",
    "formatted": "15/02/2025",
    "iso": "2025-02-14T17:00:00.000000Z",
    "human": "2 tuần tới"
  },
  "contact_email": "contact@company.com",
  "contact_phone": "phone_number",
  "company_website": "https://company.com",
  "application_method": "email",
  "is_urgent": false,
  "is_featured": true,
  "status": true,
  "company_info": {
    "name": "Company Name",
    "size": "51-200",
    "website": "https://company.com"
  },
  "created_at": "2025-01-15T10:30:00Z",
  "updated_at": "2025-01-15T12:45:00Z",
  "days_remaining": 15,
  "is_expired": false
}
```

---

## Caching

### Response Caching
- Danh sách jobs: Cache 5 phút
- Chi tiết job: Cache 10 phút
- Categories/Attributes: Cache 1 giờ
- Trending jobs: Cache 15 phút

### Cache Headers
```http
Cache-Control: public, max-age=300
ETag: "unique-etag-value"
Last-Modified: Wed, 15 Jan 2025 10:30:00 GMT
```

---

## SEO Support

### Meta Tags
Mỗi job detail page hỗ trợ đầy đủ meta tags cho SEO:
- `meta.title`: SEO optimized title
- `meta.description`: SEO description  
- `meta.keywords`: Relevant keywords

### Structured Data
Response hỗ trợ structured data cho Google Jobs:
- `@type`: "JobPosting"
- Organization info
- Salary info
- Location info
- Application details

---

## Performance

### Pagination
- Default: 15 items/page
- Max: 50 items/page
- Efficient offset-based pagination

### Database Optimization
- Indexes trên các filter fields
- Eager loading relationships
- Query optimization cho search

### Response Size
- Lightweight response cho list view
- Full details chỉ trong detail view
- Optional fields có thể exclude

---

## Changelog

### v1.0.0 (October 2025)
- ✅ Basic job listing
- ✅ Job details
- ✅ Categories & attributes
- ✅ Advanced search
- ✅ Trending jobs
- ✅ Similar jobs
- ✅ SEO support
- ✅ Caching strategy