# User Job Creation API Documentation

## Tổng quan

API User Job Creation cho phép các admin users đã đăng nhập tạo, quản lý và xem các job postings của riêng họ. API này khác với JobController hiện tại (dành cho admin) bằng cách bổ sung user ownership và validation phù hợp cho end users.

## Authentication

Tất cả endpoints yêu cầu authentication với Bearer token sử dụng Laravel Sanctum từ Admin model.

### Lấy Authentication Token

```bash
POST /api/auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password"
}
```

Response:
```json
{
    "status": "success",
    "message": "Đăng nhập thành công.",
    "data": {
        "access_token": "15|ok0WGgXPX08meXVJit6qaBoyBMACpESXb8ohEF0N51e497eb",
        "token_type": "Bearer",
        "user": {
            "id": 1,
            "name": "Example User",
            "email": "user@example.com"
        }
    }
}
```

## Base URL

```
https://lamgame.localhost/api/user/jobs/
```

## Rate Limiting

- 60 requests per minute per authenticated user
- Headers trả về: `X-RateLimit-Limit`, `X-RateLimit-Remaining`

---

## Job System Architecture

### Database Structure

**Jobs được lưu trong Bagisto EAV system:**

1. **products table** - Basic job information
   - `id`, `sku`, `type`, `attribute_family_id`, `created_by_admin_id`
2. **product_attribute_values table** - Job details
   - Kết nối với attributes để lưu thông tin chi tiết
3. **product_categories table** - Job categorization
   - Jobs thuộc category "Việc Làm" (ID: 102)

### Job Attributes Available

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `title` | string | ✅ | Tiêu đề công việc (max: 255) |
| `description` | string | ✅ | Mô tả chi tiết (min: 100) |
| `short_description` | string | | Mô tả ngắn (max: 500) |
| `job_type` | enum | ✅ | `full-time`, `part-time`, `contract`, `freelance`, `internship` |
| `experience_level` | enum | ✅ | `entry`, `junior`, `mid`, `senior`, `lead`, `executive` |
| `salary_range` | string | | Mức lương mong muốn |
| `job_location` | string | ✅ | Địa điểm làm việc |
| `company_name` | string | ✅ | Tên công ty |
| `company_size` | enum | | `1-10`, `11-50`, `51-200`, `201-500`, `500+` |
| `company_website` | url | | Website công ty |
| `required_skills` | array | | Danh sách kỹ năng yêu cầu |
| `education_level` | enum | | `high-school`, `bachelor`, `master`, `phd`, `none` |
| `english_level` | enum | | `basic`, `intermediate`, `advanced`, `native` |
| `job_benefits` | array | | Danh sách phúc lợi |
| `application_deadline` | date | | Hạn ứng tuyển (sau hôm nay) |
| `contact_email` | email | ✅ | Email liên hệ |
| `contact_phone` | string | | Số điện thoại |
| `is_urgent` | boolean | | Việc gấp |
| `is_featured` | boolean | | Việc nổi bật |
| `categories` | array | | Sub-categories (optional) |
| `meta_title` | string | | SEO title |
| `meta_description` | string | | SEO description |
| `meta_keywords` | string | | SEO keywords |

---

## Endpoints

### 1. Get User's Jobs

Lấy danh sách jobs của user đã đăng nhập.

**Endpoint:** `GET /api/user/jobs/`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `search` (string): Tìm kiếm trong title và description
- `status` (string): Filter theo status (`active`, `inactive`)
- `sort` (string): Sort field (default: `created_at`)
- `direction` (string): Sort direction (`asc`, `desc`)
- `per_page` (int): Items per page (max: 50, default: 15)

**Response:**
```json
{
    "success": true,
    "message": "Jobs retrieved successfully",
    "data": [
        {
            "id": 29,
            "sku": "JOB_DEVSTUDIO_SOLUTIONS_FRONTE_2025",
            "title": "Frontend Developer - Vue.js Expert",
            "slug": "frontend-developer-vuejs-expert",
            "short_description": "Vue.js expert needed for modern web app development.",
            "description": "Looking for a skilled Vue.js developer...",
            "job_type": "Full-time",
            "experience_level": "mid",
            "salary_range": "1500-2500 USD",
            "job_location": "Remote",
            "company_size": "11-50",
            "required_skills": ["Vue.js", "Nuxt.js", "TypeScript", "SCSS"],
            "education_level": "bachelor",
            "english_level": "advanced",
            "job_benefits": ["Remote Work", "Flexible Hours", "Equipment Provided"],
            "application_deadline": {
                "raw": "2025-10-31",
                "formatted": "31/10/2025",
                "iso": "2025-10-30T17:00:00.000000Z",
                "human": "4 tuần tới"
            },
            "contact_email": "jobs@devstudio.com",
            "contact_phone": "+84987654321",
            "company_website": null,
            "is_urgent": true,
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
                "title": null,
                "description": null,
                "keywords": null
            },
            "created_at": "2025-10-01T11:21:09.000000Z",
            "updated_at": "2025-10-01T11:21:56.000000Z",
            "days_remaining": 30,
            "is_expired": false,
            "company_info": {
                "name": "Vue.js Expert",
                "position": "Frontend Developer",
                "contact": {
                    "email": "jobs@devstudio.com",
                    "phone": "+84987654321",
                    "website": null
                }
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 15,
        "total": 2,
        "last_page": 1,
        "from": 1,
        "to": 2
    }
}
```

### 2. Create New Job

Tạo job posting mới.

**Endpoint:** `POST /api/user/jobs/`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "title": "Senior Laravel Developer - Test Job",
    "description": "We are looking for an experienced Laravel developer to join our dynamic team. The ideal candidate should have strong experience in PHP, Laravel framework, MySQL, and modern frontend technologies. You will be responsible for developing and maintaining web applications, working closely with our design and product teams to deliver high-quality solutions.",
    "short_description": "Experienced Laravel developer needed for dynamic team. Remote work available.",
    "job_type": "full-time",
    "experience_level": "senior",
    "salary_range": "2000-3000 USD",
    "job_location": "Ho Chi Minh City, Vietnam",
    "company_name": "TechStart Vietnam",
    "company_size": "51-200",
    "company_website": "https://techstart.vn",
    "required_skills": ["Laravel", "PHP", "MySQL", "Vue.js", "Docker"],
    "education_level": "bachelor",
    "english_level": "intermediate",
    "job_benefits": ["Health Insurance", "Flexible Working Hours", "Remote Work", "Training Budget"],
    "application_deadline": "2025-11-01",
    "contact_email": "hr@techstart.vn",
    "contact_phone": "+84901234567",
    "is_urgent": false,
    "is_featured": false,
    "meta_title": "Senior Laravel Developer Job - TechStart Vietnam",
    "meta_description": "Join TechStart Vietnam as a Senior Laravel Developer. Remote work available with competitive salary and benefits."
}
```

**Response:**
```json
{
    "success": true,
    "message": "Job created successfully",
    "data": {
        "id": 28,
        "sku": "JOB_TECHSTART_VIETNAM_SENIOR_L_2025",
        "title": "Senior Laravel Developer - Test Job",
        "slug": "senior-laravel-developer-test-job",
        "short_description": "Experienced Laravel developer needed for dynamic team. Remote work available.",
        "description": "We are looking for an experienced Laravel developer...",
        "job_type": "Full-time",
        "experience_level": "senior",
        "salary_range": "2000-3000 USD",
        "job_location": "Ho Chi Minh City, Vietnam",
        "company_size": "51-200",
        "required_skills": ["Laravel", "PHP", "MySQL", "Vue.js", "Docker"],
        "education_level": "bachelor",
        "english_level": "intermediate",
        "job_benefits": ["Health Insurance", "Flexible Working Hours", "Remote Work", "Training Budget"],
        "application_deadline": {
            "raw": "2025-11-01",
            "formatted": "01/11/2025",
            "iso": "2025-10-31T17:00:00.000000Z",
            "human": "4 tuần tới"
        },
        "contact_email": "hr@techstart.vn",
        "contact_phone": "+84901234567",
        "company_website": "https://techstart.vn",
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
            "title": "Senior Laravel Developer Job - TechStart Vietnam",
            "description": "Join TechStart Vietnam as a Senior Laravel Developer. Remote work available with competitive salary and benefits.",
            "keywords": null
        },
        "created_at": "2025-10-01T11:18:19.000000Z",
        "updated_at": "2025-10-01T11:18:19.000000Z",
        "days_remaining": 31,
        "is_expired": false,
        "company_info": {
            "name": "Test Job",
            "position": "Senior Laravel Developer",
            "contact": {
                "email": "hr@techstart.vn",
                "phone": "+84901234567",
                "website": "https://techstart.vn"
            }
        }
    }
}
```

### 3. Get Specific Job

Lấy chi tiết job cụ thể thuộc về user.

**Endpoint:** `GET /api/user/jobs/{id}`

**Parameters:**
- `id` (integer): Job ID

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
Same format as create job response.

### 4. Update Job

Cập nhật job thuộc về user.

**Endpoint:** `PUT /api/user/jobs/{id}`

**Parameters:**
- `id` (integer): Job ID

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
Same format as create job request (all fields optional).

**Response:**
```json
{
    "success": true,
    "message": "Job updated successfully",
    "data": {
        // Updated job data
    }
}
```

### 5. Delete Job

Xóa job thuộc về user.

**Endpoint:** `DELETE /api/user/jobs/{id}`

**Parameters:**
- `id` (integer): Job ID

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
    "success": true,
    "message": "Job deleted successfully"
}
```

### 6. Toggle Job Status

Bật/tắt trạng thái job (active/inactive).

**Endpoint:** `PATCH /api/user/jobs/{id}/toggle-status`

**Parameters:**
- `id` (integer): Job ID

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
    "success": true,
    "message": "Job status updated successfully",
    "data": {
        "id": 29,
        "status": "active",
        "status_value": true
    }
}
```

---

## Error Handling

### Error Response Format

```json
{
    "success": false,
    "message": "Error message",
    "error": "Detailed error description",
    "errors": {
        "field_name": [
            "Validation error message"
        ]
    }
}
```

### Common HTTP Status Codes

- `200 OK`: Request successful
- `201 Created`: Job created successfully
- `400 Bad Request`: Invalid request data
- `401 Unauthorized`: Missing or invalid token
- `403 Forbidden`: Access denied
- `404 Not Found`: Job not found or access denied
- `422 Unprocessable Entity`: Validation errors
- `429 Too Many Requests`: Rate limit exceeded
- `500 Internal Server Error`: Server error

### Validation Error Example

```json
{
    "success": false,
    "message": "Dữ liệu không hợp lệ.",
    "errors": {
        "title": [
            "Tiêu đề công việc là bắt buộc."
        ],
        "description": [
            "Mô tả công việc phải có ít nhất 100 ký tự."
        ],
        "contact_email": [
            "Email liên hệ không hợp lệ."
        ]
    }
}
```

---

## Integration Examples

### JavaScript/Fetch Example

```javascript
// Create new job
async function createJob(jobData) {
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch('/api/user/jobs/', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(jobData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            console.log('Job created:', result.data);
            return result.data;
        } else {
            console.error('Error:', result.message);
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Request failed:', error);
        throw error;
    }
}

// Get user's jobs
async function getUserJobs(params = {}) {
    const token = localStorage.getItem('auth_token');
    const queryString = new URLSearchParams(params).toString();
    const url = `/api/user/jobs/${queryString ? '?' + queryString : ''}`;
    
    try {
        const response = await fetch(url, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            return {
                jobs: result.data,
                pagination: result.pagination
            };
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Failed to fetch jobs:', error);
        throw error;
    }
}

// Usage examples
const jobData = {
    title: "Senior PHP Developer",
    description: "We are looking for an experienced PHP developer...",
    job_type: "full-time",
    experience_level: "senior",
    job_location: "Ho Chi Minh City",
    company_name: "Tech Company",
    contact_email: "hr@company.com"
};

// Create job
createJob(jobData).then(job => {
    console.log('New job created:', job.id);
});

// Get jobs with search
getUserJobs({ search: 'developer', status: 'active' }).then(data => {
    console.log('Found jobs:', data.jobs.length);
});
```

### PHP/Laravel Example

```php
<?php

use Illuminate\Support\Facades\Http;

class UserJobService
{
    protected $baseUrl;
    protected $token;
    
    public function __construct($baseUrl, $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }
    
    public function createJob(array $jobData)
    {
        $response = Http::withToken($this->token)
            ->accept('application/json')
            ->post($this->baseUrl . '/user/jobs/', $jobData);
            
        if ($response->successful()) {
            $data = $response->json();
            return $data['success'] ? $data['data'] : null;
        }
        
        throw new Exception('Failed to create job: ' . $response->body());
    }
    
    public function getUserJobs(array $params = [])
    {
        $response = Http::withToken($this->token)
            ->accept('application/json')
            ->get($this->baseUrl . '/user/jobs/', $params);
            
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new Exception('Failed to fetch jobs: ' . $response->body());
    }
    
    public function updateJob(int $jobId, array $jobData)
    {
        $response = Http::withToken($this->token)
            ->accept('application/json')
            ->put($this->baseUrl . "/user/jobs/{$jobId}", $jobData);
            
        if ($response->successful()) {
            $data = $response->json();
            return $data['success'] ? $data['data'] : null;
        }
        
        throw new Exception('Failed to update job: ' . $response->body());
    }
    
    public function deleteJob(int $jobId)
    {
        $response = Http::withToken($this->token)
            ->accept('application/json')
            ->delete($this->baseUrl . "/user/jobs/{$jobId}");
            
        return $response->successful();
    }
}

// Usage
$jobService = new UserJobService('https://lamgame.localhost/api', $userToken);

$jobData = [
    'title' => 'Frontend Developer',
    'description' => 'Looking for a skilled Vue.js developer...',
    'job_type' => 'full-time',
    'experience_level' => 'mid',
    'job_location' => 'Remote',
    'company_name' => 'DevStudio',
    'contact_email' => 'jobs@devstudio.com'
];

$job = $jobService->createJob($jobData);
```

### cURL Examples

```bash
# Create job
curl -X POST "https://lamgame.localhost/api/user/jobs/" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "title": "Senior Developer",
    "description": "Looking for experienced developer with 5+ years...",
    "job_type": "full-time",
    "experience_level": "senior",
    "job_location": "Ho Chi Minh City",
    "company_name": "Tech Company",
    "contact_email": "hr@company.com"
  }'

# Get user jobs
curl -X GET "https://lamgame.localhost/api/user/jobs/?search=developer&status=active" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Get specific job
curl -X GET "https://lamgame.localhost/api/user/jobs/29" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Update job
curl -X PUT "https://lamgame.localhost/api/user/jobs/29" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "title": "Updated Job Title",
    "salary_range": "3000-4000 USD"
  }'

# Delete job
curl -X DELETE "https://lamgame.localhost/api/user/jobs/29" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Toggle job status
curl -X PATCH "https://lamgame.localhost/api/user/jobs/29/toggle-status" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Security Considerations

1. **Authentication Required**: Tất cả endpoints require valid Bearer token
2. **User Ownership**: Users chỉ có thể truy cập jobs của họ
3. **Input Validation**: Comprehensive validation với custom messages
4. **Rate Limiting**: 60 requests/minute per user
5. **SQL Injection Protection**: Sử dụng Eloquent ORM
6. **XSS Protection**: Input sanitization và output escaping

---

## Business Logic

### Job Ownership
- Jobs được associate với user qua `created_by_admin_id` field
- Users chỉ có thể CRUD jobs của chính họ
- Không thể truy cập jobs của users khác

### Job Categories
- Tất cả jobs được tự động gán vào category "Việc Làm" (ID: 102)
- Users có thể thêm sub-categories nếu có

### SKU Generation
- Auto-generated với format: `JOB_COMPANY_TITLE_YEAR`
- Unique constraint đảm bảo không trùng lặp

### Status Management
- Jobs có thể active (hiển thị public) hoặc inactive (ẩn)
- Toggle status API cho phép bật/tắt nhanh chóng

---

## Troubleshooting

### Common Issues

1. **401 Unauthorized**
   - Kiểm tra token validity và Bearer prefix
   - Token có thể đã expire, cần login lại

2. **404 Not Found hoặc Access Denied**
   - Job không tồn tại hoặc không thuộc về user
   - Kiểm tra job ID và ownership

3. **422 Validation Errors**
   - Required fields missing: `title`, `description`, `job_type`, `experience_level`, `job_location`, `company_name`, `contact_email`
   - Format errors: email không hợp lệ, date không đúng format
   - Length errors: description quá ngắn (<100 chars)

4. **429 Rate Limit Exceeded**
   - Implement delay giữa các requests
   - Cache responses khi có thể

### Debugging Steps

1. Check authentication token
2. Verify request format và required fields
3. Confirm user has ownership of job (for update/delete operations)
4. Review validation errors trong response
5. Check server logs for detailed error information

---

## Changelog

### Version 1.0.0 (2025-10-01)
- ✅ Initial release
- ✅ Complete CRUD operations for user jobs
- ✅ User ownership and access control
- ✅ Comprehensive validation with Vietnamese messages
- ✅ Integration with Bagisto EAV system
- ✅ Rate limiting and security measures
- ✅ Detailed API documentation with examples