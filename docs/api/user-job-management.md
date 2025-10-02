# User Job Management API

API này cho phép admin/user đã đăng nhập quản lý các bài tuyển dụng của riêng họ.

## Base URL
```
/api/user/jobs
```

## Authentication
Tất cả endpoints yêu cầu xác thực qua Sanctum token:
```http
Authorization: Bearer {your-sanctum-token}
```

## Rate Limiting
- **60 requests/phút** theo IP
- Headers trả về: `X-RateLimit-Limit`, `X-RateLimit-Remaining`

---

## 1. Lấy danh sách job của user

### `GET /api/user/jobs`

Lấy danh sách tất cả bài tuyển dụng do user hiện tại tạo.

#### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `search` | string | No | Tìm kiếm theo title hoặc description |
| `status` | string | No | Lọc theo trạng thái: `active`, `inactive` |
| `sort` | string | No | Sắp xếp theo: `created_at`, `updated_at` (default: `created_at`) |
| `direction` | string | No | Hướng sắp xếp: `asc`, `desc` (default: `desc`) |
| `per_page` | integer | No | Số item/trang (default: 15, max: 50) |

#### Request Example
```bash
curl -X GET "https://lamgame.localhost/api/user/jobs?search=developer&status=active&per_page=10" \
  -H "Authorization: Bearer your-token-here" \
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
      "sku": "JOB_TECHCORP_DEVELOPER_2025",
      "title": "Senior PHP Developer",
      "slug": "senior-php-developer",
      "short_description": "Senior developer position",
      "description": "We are looking for...",
      "job_type": "Full-time",
      "experience_level": "senior",
      "salary_range": "20-30 triệu VND",
      "job_location": "Ho Chi Minh City",
      "company_size": "51-200",
      "required_skills": ["PHP", "Laravel", "MySQL"],
      "education_level": "bachelor",
      "english_level": "intermediate",
      "job_benefits": ["health-insurance", "13th-salary"],
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
      "is_featured": false,
      "status": true,
      "categories": [
        {
          "id": 102,
          "name": "Việc Làm",
          "slug": "viec-lam"
        }
      ],
      "meta": {
        "title": "SEO title",
        "description": "SEO description",
        "keywords": "SEO keywords"
      },
      "created_at": "2025-01-15T10:30:00Z",
      "updated_at": "2025-01-15T10:30:00Z",
      "days_remaining": 15,
      "is_expired": false,
      "company_info": {
        "name": "TechCorp",
        "position": "Senior PHP Developer",
        "contact": {
          "email": "hr@techcorp.com",
          "phone": "0901234567",
          "website": "https://techcorp.com"
        }
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 25,
    "last_page": 2,
    "from": 1,
    "to": 15
  }
}
```

---

## 2. Tạo bài tuyển dụng mới

### `POST /api/user/jobs`

Tạo một bài tuyển dụng mới.

#### Request Body Schema

| Field | Type | Required | Validation |
|-------|------|----------|------------|
| `title` | string | **Yes** | max:255 |
| `company_name` | string | **Yes** | max:255 |
| `description` | string | **Yes** | min:100 |
| `short_description` | string | No | max:500 |
| `job_type` | string | **Yes** | in:full-time,part-time,contract,freelance,internship |
| `experience_level` | string | **Yes** | in:entry,junior,mid,senior,lead,executive |
| `salary_range` | string | No | max:100 |
| `job_location` | string | **Yes** | max:255 |
| `company_size` | string | No | in:1-10,11-50,51-200,201-500,500+ |
| `required_skills` | array | No | array of strings, max:100 each |
| `education_level` | string | No | in:high-school,bachelor,master,phd,none |
| `english_level` | string | No | in:basic,intermediate,advanced,native |
| `job_benefits` | array | No | array of strings, max:100 each |
| `application_deadline` | date | No | date format, after today |
| `contact_email` | email | **Yes** | valid email, max:255 |
| `contact_phone` | string | No | max:20 |
| `company_website` | url | No | valid URL |
| `is_urgent` | boolean | No | default: false |
| `is_featured` | boolean | No | default: false |
| `meta_title` | string | No | max:255 |
| `meta_description` | string | No | max:500 |
| `meta_keywords` | string | No | max:255 |

#### Request Example
```bash
curl -X POST "https://lamgame.localhost/api/user/jobs" \
  -H "Authorization: Bearer your-token-here" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Senior PHP Developer",
    "company_name": "TechCorp Vietnam",
    "description": "We are looking for an experienced PHP developer to join our growing team. The ideal candidate will have strong experience with Laravel framework and modern development practices. This is a great opportunity to work on exciting projects.",
    "short_description": "Senior developer position with Laravel expertise",
    "job_type": "full-time",
    "experience_level": "senior",
    "salary_range": "20-30 triệu VND",
    "job_location": "Ho Chi Minh City",
    "company_size": "51-200",
    "required_skills": ["PHP", "Laravel", "MySQL", "Git"],
    "education_level": "bachelor",
    "english_level": "intermediate",
    "job_benefits": ["health-insurance", "13th-salary", "flexible-hours"],
    "application_deadline": "2025-02-15",
    "contact_email": "hr@techcorp.com",
    "contact_phone": "0901234567",
    "company_website": "https://techcorp.com",
    "is_urgent": false,
    "is_featured": false
  }'
```

#### Response Success (201)
```json
{
  "success": true,
  "message": "Job created successfully",
  "data": {
    "id": 124,
    "sku": "JOB_TECHCORP_VIETNAM_SENIOR_P_2025",
    "title": "Senior PHP Developer",
    "company_name": "TechCorp Vietnam",
    "description": "We are looking for an experienced PHP developer...",
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
      "human": "6 tuần tới"
    },
    "contact_email": "hr@techcorp.com",
    "contact_phone": "0901234567",
    "company_website": "https://techcorp.com",
    "application_method": null,
    "is_urgent": false,
    "is_featured": false,
    "status": true,
    "categories": [
      {
        "id": 102,
        "name": "Việc Làm",
        "slug": "viec-lam"
      }
    ],
    "created_at": "2025-01-15T14:20:00Z",
    "updated_at": "2025-01-15T14:20:00Z"
  }
}
```

#### Response Error (422)
```json
{
  "message": "Mô tả công việc là bắt buộc. (and 5 more errors)",
  "errors": {
    "description": ["Mô tả công việc là bắt buộc."],
    "job_type": ["Loại hình công việc là bắt buộc."],
    "experience_level": ["Mức kinh nghiệm là bắt buộc."],
    "job_location": ["Địa điểm làm việc là bắt buộc."],
    "company_name": ["Tên công ty là bắt buộc."],
    "contact_email": ["Email liên hệ là bắt buộc."]
  }
}
```

---

## 3. Lấy chi tiết một job

### `GET /api/user/jobs/{id}`

Lấy chi tiết một bài tuyển dụng cụ thể thuộc về user hiện tại.

#### Request Example
```bash
curl -X GET "https://lamgame.localhost/api/user/jobs/123" \
  -H "Authorization: Bearer your-token-here" \
  -H "Accept: application/json"
```

#### Response Success (200)
```json
{
  "success": true,
  "message": "Job retrieved successfully",
  "data": {
    "id": 123,
    "sku": "JOB_TECHCORP_DEVELOPER_2025",
    "title": "Senior PHP Developer",
    "slug": "senior-php-developer",
    // ... full job details như trong response của POST
    "created_at": "2025-01-15T10:30:00Z",
    "updated_at": "2025-01-15T10:30:00Z"
  }
}
```

#### Response Error (404)
```json
{
  "success": false,
  "message": "Job not found or access denied",
  "error": "The job does not exist or you do not have permission to view it"
}
```

---

## 4. Cập nhật job

### `PUT /api/user/jobs/{id}`

Cập nhật thông tin bài tuyển dụng thuộc về user hiện tại.

#### Request Body
Same as POST request body. Tất cả fields đều optional khi update.

#### Request Example
```bash
curl -X PUT "https://lamgame.localhost/api/user/jobs/123" \
  -H "Authorization: Bearer your-token-here" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Senior PHP Developer - UPDATED",
    "salary_range": "25-35 triệu VND",
    "is_urgent": true
  }'
```

#### Response Success (200)
```json
{
  "success": true,
  "message": "Job updated successfully", 
  "data": {
    "id": 123,
    "title": "Senior PHP Developer - UPDATED",
    "salary_range": "25-35 triệu VND",
    "is_urgent": true,
    // ... other job data
    "updated_at": "2025-01-15T16:45:00Z"
  }
}
```

---

## 5. Xóa job

### `DELETE /api/user/jobs/{id}`

Xóa bài tuyển dụng thuộc về user hiện tại.

#### Request Example
```bash
curl -X DELETE "https://lamgame.localhost/api/user/jobs/123" \
  -H "Authorization: Bearer your-token-here" \
  -H "Accept: application/json"
```

#### Response Success (200)
```json
{
  "success": true,
  "message": "Job deleted successfully"
}
```

#### Response Error (404)
```json
{
  "success": false,
  "message": "Job not found or access denied",
  "error": "The job does not exist or you do not have permission to delete it"
}
```

---

## 6. Bật/tắt trạng thái job

### `PATCH /api/user/jobs/{id}/toggle-status`

Chuyển đổi trạng thái active/inactive của bài tuyển dụng.

#### Request Example
```bash
curl -X PATCH "https://lamgame.localhost/api/user/jobs/123/toggle-status" \
  -H "Authorization: Bearer your-token-here" \
  -H "Accept: application/json"
```

#### Response Success (200)
```json
{
  "success": true,
  "message": "Job status updated successfully",
  "data": {
    "id": 123,
    "status": "inactive",
    "status_value": false
  }
}
```

---

## Error Responses

### Authentication Error (401)
```json
{
  "message": "Unauthenticated."
}
```

### Rate Limit Exceeded (429)
```json
{
  "message": "Too Many Attempts."
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Failed to create/update/delete job",
  "error": "Internal server error"
}
```

---

## Business Rules

### Ownership
- User chỉ có thể thao tác với các bài tuyển dụng do chính họ tạo
- `created_by_admin_id` được tự động gán = user ID hiện tại
- Không thể xem/sửa/xóa job của user khác

### SKU Generation
- System tự động tạo SKU unique cho mỗi job
- Format: `JOB_{COMPANY}_{TITLE}_{YEAR}[_{COUNTER}]`
- VD: `JOB_TECHCORP_VIETNAM_SENIOR_P_2025`

### Category Assignment
- Tất cả jobs được tự động gán vào category "Việc Làm" (ID: 102)
- User không thể thay đổi category này

### Validation
- Sử dụng `CreateUserJobRequest` để validate dữ liệu
- Error messages bằng tiếng Việt
- Required fields được validate nghiêm ngặt

### Logging
- Tất cả thao tác quan trọng (tạo, sửa, xóa) đều được log
- Log format: `[timestamp] [user_id] [action] [job_id] [details]`

---

## Performance Notes

### Caching
- Job data không được cache để đảm bảo real-time
- Category data có thể được cache ngắn hạn

### Database Queries
- Sử dụng eager loading cho relationships
- Index trên `created_by_admin_id` để tối ưu query performance

### Rate Limiting
- Giới hạn 60 requests/phút để tránh spam
- Có thể điều chỉnh cho từng user nếu cần

---

## Testing

Xem [Job API Testing Guide](job-api-testing.md) để biết cách test API này một cách chi tiết.

---

## Security

### Authorization
- Middleware `auth:sanctum` đảm bảo chỉ user đã đăng nhập mới access được
- Ownership check ở mọi endpoint để đảm bảo security

### Input Validation
- Tất cả input đều được validate nghiêm ngặt
- Sanitize HTML content trong description
- Validate email format và URL format

### HTTPS Required
- Tất cả requests phải qua HTTPS
- Token được truyền qua Authorization header

---

## Changelog

### v1.0.0 (October 2025)
- ✅ Initial release
- ✅ Full CRUD operations
- ✅ Search và filtering
- ✅ Pagination support
- ✅ Ownership validation
- ✅ Vietnamese error messages