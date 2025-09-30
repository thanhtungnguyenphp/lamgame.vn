# 📖 Job Posting API - Tài Liệu Tích Hợp

## 📋 Tổng Quan

Job Posting API của LamGame.vn cung cấp các endpoints để quản lý tin tuyển dụng game development. API được xây dựng trên Laravel framework và sử dụng EAV (Entity-Attribute-Value) model để linh hoạt trong quản lý dữ liệu.

### 🌐 Base URL
```
http://localhost:8000/api/jobs
```

### 📄 Response Format
Tất cả API responses đều theo format JSON chuẩn:

```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": { ... },
  "pagination": { ... } // Chỉ có khi phân trang
}
```

### ⚠️ Error Handling
Khi có lỗi, API trả về format:

```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error message",
  "errors": { ... } // Chi tiết lỗi validation
}
```

---

## 🚀 API Endpoints

### 1. 📋 **GET /api/jobs** - Lấy Danh Sách Tin Tuyển Dụng

#### **Mô Tả**
Lấy danh sách tất cả tin tuyển dụng với khả năng filter, search và pagination.

#### **Parameters**
| Tham số | Kiểu | Mô tả | Ví dụ |
|---------|------|-------|--------|
| `search` | string | Tìm kiếm trong title, description | `Unity` |
| `job_type` | string | Loại công việc | `full-time`, `part-time`, `remote` |
| `location` | string | Địa điểm làm việc | `Hồ Chí Minh` |
| `company` | string | Tên công ty | `VNG` |
| `is_urgent` | boolean | Chỉ lấy tin tuyển gấp | `true`, `false` |
| `is_featured` | boolean | Chỉ lấy tin nổi bật | `true`, `false` |
| `per_page` | integer | Số item/trang (max 50) | `15` |
| `order_by` | string | Sắp xếp theo | `created_at`, `deadline` |
| `order_direction` | string | Hướng sắp xếp | `desc`, `asc` |

#### **Request Example**
```bash
GET /api/jobs?search=Unity&job_type=full-time&location=Hồ Chí Minh&per_page=10&is_featured=1
```

#### **Response Example**
```json
{
  "success": true,
  "message": "Job postings retrieved successfully",
  "data": [
    {
      "id": 1,
      "sku": "JOB_VNG_UNITY_DEV_2025",
      "title": "Unity Developer - VNG Corporation",
      "slug": "unity-developer-vng-corporation",
      "short_description": "VNG tuyển Unity Developer 5+ năm kinh nghiệm...",
      "description": "<h2>Job Description</h2>...",
      "job_type": "Full-time",
      "experience_level": "Senior (5+ năm)",
      "salary_range": "50-80 triệu",
      "job_location": "Hồ Chí Minh",
      "company_size": "Tập đoàn (1000+ người)",
      "required_skills": ["Unity", "C#", "Git"],
      "education_level": "Đại học",
      "english_level": "Thành thạo",
      "job_benefits": ["Bảo hiểm sức khỏe", "Thưởng hiệu suất"],
      "application_deadline": {
        "raw": "2025-12-31",
        "formatted": "31/12/2025",
        "iso": "2025-12-31T00:00:00Z",
        "human": "trong 2 tháng"
      },
      "contact_email": "careers@vng.com.vn",
      "contact_phone": "028-3835-1234",
      "company_website": "https://www.vng.com.vn",
      "application_method": "Ứng tuyển online",
      "is_urgent": false,
      "is_featured": true,
      "status": true,
      "categories": [
        {
          "id": 102,
          "name": "Việc Làm",
          "slug": "viec-lam",
          "url_path": "viec-lam"
        }
      ],
      "meta": {
        "title": "Unity Developer - VNG Corporation | LamGame Jobs",
        "description": "VNG tuyển Unity Developer 5+ năm kinh nghiệm...",
        "keywords": "game jobs, tuyển dụng game"
      },
      "created_at": "2025-09-30T05:00:00Z",
      "updated_at": "2025-09-30T05:00:00Z",
      "days_remaining": 92,
      "is_expired": false,
      "company_info": {
        "name": "VNG Corporation",
        "position": "Unity Developer",
        "contact": {
          "email": "careers@vng.com.vn",
          "phone": "028-3835-1234",
          "website": "https://www.vng.com.vn"
        }
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 25,
    "last_page": 3,
    "from": 1,
    "to": 10
  },
  "filters_applied": {
    "search": "Unity",
    "job_type": "full-time",
    "location": "Hồ Chí Minh",
    "is_featured": true
  }
}
```

#### **cURL Example**
```bash
curl -X GET "http://localhost:8000/api/jobs?search=Unity&per_page=5" \
  -H "Accept: application/json"
```

---

### 2. ✨ **POST /api/jobs** - Tạo Tin Tuyển Dụng Mới

#### **Mô Tả**
Tạo một tin tuyển dụng mới với đầy đủ thông tin và validation.

#### **Request Body**
```json
{
  "title": "Unity Developer - VNG Corporation",
  "company_name": "VNG Corporation",
  "description": "<h2>Job Description</h2><p>Detailed job description...</p>",
  "short_description": "Brief job summary (max 500 chars)",
  "job_type": "full-time",
  "experience_level": "senior",
  "salary_range": "50m-80m",
  "job_location": "Hồ Chí Minh",
  "company_size": "Tập đoàn (1000+ người)",
  "required_skills": ["Unity", "C#", "Git"],
  "education_level": "Đại học",
  "english_level": "Thành thạo",
  "job_benefits": ["Bảo hiểm sức khỏe", "Thưởng hiệu suất"],
  "application_deadline": "2025-12-31",
  "contact_email": "careers@vng.com.vn",
  "contact_phone": "028-3835-1234",
  "company_website": "https://www.vng.com.vn",
  "application_method": "online",
  "is_urgent": false,
  "is_featured": true,
  "categories": [102],
  "meta_title": "Unity Developer - VNG Corporation | LamGame Jobs",
  "meta_description": "Job posting meta description for SEO",
  "meta_keywords": "unity, developer, game, vng"
}
```

#### **Required Fields**
- `title` (string, max 255)
- `company_name` (string, max 255)
- `description` (string)
- `short_description` (string, max 500)
- `job_type` (enum: full-time, part-time, contract, freelance, internship, remote, hybrid)
- `experience_level` (enum: fresher, junior, middle, senior, lead, director)
- `salary_range` (string)
- `job_location` (string)
- `company_size` (string)
- `required_skills` (array, min 1 item)
- `contact_email` (email)
- `application_method` (enum: email, online, direct, website)
- `categories` (array, min 1 item)

#### **Response Example**
```json
{
  "success": true,
  "message": "Job posting created successfully",
  "data": {
    // Job object như trong GET response
    "id": 15,
    "sku": "JOB_VNG_UNITY_DEV_2025_2",
    // ... other fields
  }
}
```

#### **cURL Example**
```bash
curl -X POST "http://localhost:8000/api/jobs" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Unity Developer - Test Company",
    "company_name": "Test Company",
    "description": "<p>Test job description</p>",
    "short_description": "Test job summary",
    "job_type": "full-time",
    "experience_level": "junior",
    "salary_range": "20m-30m",
    "job_location": "Hà Nội",
    "company_size": "Nhỏ (10-50 người)",
    "required_skills": ["Unity", "C#"],
    "contact_email": "hr@testcompany.com",
    "application_method": "email",
    "categories": [102]
  }'
```

---

### 3. 🔍 **GET /api/jobs/{id}** - Lấy Chi Tiết Tin Tuyển Dụng

#### **Mô Tả**
Lấy thông tin chi tiết của một tin tuyển dụng cụ thể theo ID.

#### **Parameters**
- `id` (integer, required) - ID của tin tuyển dụng

#### **Request Example**
```bash
GET /api/jobs/15
```

#### **Response Example**
```json
{
  "success": true,
  "message": "Job posting retrieved successfully",
  "data": {
    // Full job object với tất cả thông tin chi tiết
    "id": 15,
    "sku": "JOB_VNG_UNITY_DEV_2025_2",
    // ... complete job data
  }
}
```

#### **Error Response (404)**
```json
{
  "success": false,
  "message": "Job posting not found",
  "error": "The specified job posting does not exist"
}
```

#### **cURL Example**
```bash
curl -X GET "http://localhost:8000/api/jobs/15" \
  -H "Accept: application/json"
```

---

### 4. ✏️ **PUT /api/jobs/{id}** - Cập Nhật Tin Tuyển Dụng

#### **Mô Tả**
Cập nhật thông tin của tin tuyển dụng. Chỉ cần gửi các field muốn cập nhật.

#### **Parameters**
- `id` (integer, required) - ID của tin tuyển dụng

#### **Request Body** (Partial Update)
```json
{
  "title": "Updated Job Title",
  "salary_range": "60m-90m",
  "is_urgent": true,
  "status": true
}
```

#### **Response Example**
```json
{
  "success": true,
  "message": "Job posting updated successfully",
  "data": {
    // Updated job object
  }
}
```

#### **cURL Example**
```bash
curl -X PUT "http://localhost:8000/api/jobs/15" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Senior Unity Developer - VNG Corporation",
    "salary_range": "60m-90m",
    "is_urgent": true
  }'
```

---

### 5. 🗑️ **DELETE /api/jobs/{id}** - Xóa Tin Tuyển Dụng

#### **Mô Tả**
Xóa một tin tuyển dụng theo ID.

#### **Parameters**
- `id` (integer, required) - ID của tin tuyển dụng

#### **Response Example**
```json
{
  "success": true,
  "message": "Job posting deleted successfully"
}
```

#### **cURL Example**
```bash
curl -X DELETE "http://localhost:8000/api/jobs/15" \
  -H "Accept: application/json"
```

---

### 6. 📂 **GET /api/jobs/categories** - Lấy Danh Mục Công Việc

#### **Mô Tả**
Lấy danh sách tất cả categories liên quan đến việc làm.

#### **Response Example**
```json
{
  "success": true,
  "message": "Job categories retrieved successfully",
  "data": [
    {
      "id": 103,
      "name": "Lập Trình Game",
      "slug": "game-programming",
      "url_path": "viec-lam/game-programming",
      "description": "Vị trí lập trình viên game, engine developer"
    },
    {
      "id": 104,
      "name": "Thiết Kế Game",
      "slug": "game-design",
      "url_path": "viec-lam/game-design",
      "description": "Game designer, level designer, gameplay designer"
    },
    // ... more categories
  ]
}
```

#### **cURL Example**
```bash
curl -X GET "http://localhost:8000/api/jobs/categories" \
  -H "Accept: application/json"
```

---

### 7. 🏷️ **GET /api/jobs/attributes** - Lấy Thuộc Tính Công Việc

#### **Mô Tả**
Lấy danh sách tất cả job attributes và các options có thể chọn.

#### **Response Example**
```json
{
  "success": true,
  "message": "Job attributes retrieved successfully",
  "data": [
    {
      "code": "job_type",
      "name": "Loại Hình Công Việc",
      "type": "select",
      "is_required": false,
      "is_filterable": true,
      "options": [
        {
          "id": 62,
          "value": "Full-time",
          "sort_order": 1
        },
        {
          "id": 63,
          "value": "Part-time",
          "sort_order": 2
        },
        // ... more options
      ]
    },
    {
      "code": "experience_level",
      "name": "Cấp Độ Kinh Nghiệm",
      "type": "select",
      "is_required": false,
      "is_filterable": true,
      "options": [
        {
          "id": 69,
          "value": "Fresher (0-1 năm)",
          "sort_order": 1
        },
        // ... more options
      ]
    },
    // ... more attributes
  ]
}
```

#### **cURL Example**
```bash
curl -X GET "http://localhost:8000/api/jobs/attributes" \
  -H "Accept: application/json"
```

---

### 8. 📦 **POST /api/jobs/bulk** - Tạo Nhiều Tin Tuyển Dụng

#### **Mô Tả**
Tạo nhiều tin tuyển dụng cùng lúc (tối đa 10 jobs/request).

#### **Request Body**
```json
{
  "jobs": [
    {
      "title": "Unity Developer - Company A",
      "company_name": "Company A",
      // ... complete job data
    },
    {
      "title": "Game Designer - Company B", 
      "company_name": "Company B",
      // ... complete job data
    }
  ]
}
```

#### **Response Example**
```json
{
  "success": true,
  "message": "Bulk creation completed. 2 jobs created, 0 errors",
  "data": {
    "created_jobs": [
      // Array of created job objects
    ],
    "errors": {
      // Any validation errors by index
    }
  },
  "summary": {
    "total_attempted": 2,
    "successful": 2,
    "failed": 0
  }
}
```

#### **cURL Example**
```bash
curl -X POST "http://localhost:8000/api/jobs/bulk" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "jobs": [
      {
        "title": "Unity Developer - Company A",
        "company_name": "Company A",
        "description": "<p>Job A description</p>",
        "short_description": "Job A summary",
        "job_type": "full-time",
        "experience_level": "junior",
        "salary_range": "20m-30m",
        "job_location": "Hồ Chí Minh",
        "company_size": "Nhỏ (10-50 người)",
        "required_skills": ["Unity"],
        "contact_email": "hr@companya.com",
        "application_method": "email",
        "categories": [102]
      }
    ]
  }'
```

---

### 9. 📢 **POST /api/jobs/{id}/publish** - Publish Tin Tuyển Dụng

#### **Mô Tả**
Kích hoạt/hiển thị tin tuyển dụng (set status = true).

#### **Response Example**
```json
{
  "success": true,
  "message": "Job posting published successfully",
  "data": {
    // Updated job object với status = true
  }
}
```

#### **cURL Example**
```bash
curl -X POST "http://localhost:8000/api/jobs/15/publish" \
  -H "Accept: application/json"
```

---

### 10. 🚫 **POST /api/jobs/{id}/unpublish** - Unpublish Tin Tuyển Dụng

#### **Mô Tả**
Ẩn tin tuyển dụng (set status = false).

#### **Response Example**
```json
{
  "success": true,
  "message": "Job posting unpublished successfully",
  "data": {
    // Updated job object với status = false
  }
}
```

#### **cURL Example**
```bash
curl -X POST "http://localhost:8000/api/jobs/15/unpublish" \
  -H "Accept: application/json"
```

---

## 🔧 Technical Implementation

### Rate Limiting
- Tất cả endpoints có rate limit: **60 requests/minute**
- Bulk operations: **10 jobs/request maximum**

### Authentication
```php
// Hiện tại: API không yêu cầu authentication (for testing)
// Production: Uncomment auth:sanctum middleware in routes/api.php

Route::middleware('auth:sanctum')->group(function () {
    // Protected endpoints
});
```

### Error Codes
| HTTP Code | Meaning |
|-----------|---------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 404 | Not Found |
| 422 | Validation Error |
| 429 | Rate Limited |
| 500 | Internal Server Error |

---

## 🧪 Testing & Development

### Quick Test Script
```bash
# Chạy test script có sẵn
php test_api.php

# Hoặc test manual từng endpoint
curl -X GET "http://localhost:8000/api/jobs?per_page=5" -H "Accept: application/json"
```

### Database Requirements
- Phải có category ID 102 cho "Việc Làm"
- Job attributes phải được setup đầy đủ
- Database connection phải hoạt động

### Development Server
```bash
# Khởi động Laravel development server
php artisan serve --host=0.0.0.0 --port=8000

# Hoặc với Docker
docker-compose up -d
```

---

## 📱 Frontend Integration Examples

### JavaScript/Fetch
```javascript
// Lấy danh sách jobs
const fetchJobs = async (filters = {}) => {
  const params = new URLSearchParams(filters);
  const response = await fetch(`/api/jobs?${params}`, {
    headers: {
      'Accept': 'application/json'
    }
  });
  return response.json();
};

// Tạo job mới
const createJob = async (jobData) => {
  const response = await fetch('/api/jobs', {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(jobData)
  });
  return response.json();
};
```

### React Hook Example
```jsx
import { useState, useEffect } from 'react';

const useJobs = (filters) => {
  const [jobs, setJobs] = useState([]);
  const [loading, setLoading] = useState(true);
  
  useEffect(() => {
    const fetchJobs = async () => {
      try {
        const params = new URLSearchParams(filters);
        const response = await fetch(`/api/jobs?${params}`);
        const data = await response.json();
        setJobs(data.data);
      } catch (error) {
        console.error('Failed to fetch jobs:', error);
      } finally {
        setLoading(false);
      }
    };
    
    fetchJobs();
  }, [filters]);
  
  return { jobs, loading };
};
```

### Vue.js Composition API
```vue
<script setup>
import { ref, onMounted } from 'vue'

const jobs = ref([])
const loading = ref(true)

const fetchJobs = async (filters = {}) => {
  try {
    const params = new URLSearchParams(filters)
    const response = await fetch(`/api/jobs?${params}`)
    const data = await response.json()
    jobs.value = data.data
  } catch (error) {
    console.error('Failed to fetch jobs:', error)
  } finally {
    loading.value = false
  }
}

onMounted(() => fetchJobs())
</script>
```

---

## 🚨 Common Issues & Troubleshooting

### 1. Database Connection Error
```
SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo for lg-mysql failed
```
**Giải pháp:** Kiểm tra Docker containers hoặc update DB_HOST trong .env

### 2. Category Not Found
```json
{
  "success": false,
  "message": "Job categories not found"
}
```
**Giải pháp:** Chạy seeder để tạo job categories:
```bash
php artisan db:seed --class=JobPostingSeeder
```

### 3. Validation Errors
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "title": ["Tiêu đề công việc là bắt buộc"],
    "categories": ["Phải chọn ít nhất 1 danh mục"]
  }
}
```
**Giải pháp:** Kiểm tra required fields và format data đúng

### 4. Route Not Found (404)
**Giải pháp:** 
- Clear route cache: `php artisan route:clear`
- Check route list: `php artisan route:list --path=api/jobs`

### 5. Server Not Responding
**Giải pháp:**
- Khởi động server: `php artisan serve --port=8000`
- Kiểm tra firewall/port forwarding
- Check logs: `tail -f storage/logs/laravel.log`

---

## 📞 Support & Contact

- **GitHub Issues:** Report bugs và feature requests
- **Email:** salegamevui@gmail.com  
- **Documentation:** Cập nhật tài liệu này khi có thay đổi API

---

**🎉 Happy Coding!** 

API này đã sẵn sàng để tích hợp vào frontend applications hoặc third-party systems. Đảm bảo test kỹ trước khi deploy production.