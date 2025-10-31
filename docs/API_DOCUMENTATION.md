# LamGame.vn - API Documentation

## Thông tin chung

**Base URL:** `https://lamgame.localhost`  
**Authentication:** Bearer Token (Laravel Sanctum)  
**Content-Type:** `application/json`

## Mục lục

- [Authentication API](#authentication-api)
- [Jobs API - Public](#jobs-api---public)
- [Jobs API - Management](#jobs-api---management)
- [Job Options API](#job-options-api)
- [User Jobs API](#user-jobs-api)
- [Dashboard API](#dashboard-api)
- [Job Analytics API](#job-analytics-api)
- [Job Bulk Operations API](#job-bulk-operations-api)
- [Job Import/Export API](#job-importexport-api)
- [Job Applications API](#job-applications-api)

---

## Authentication API

### 1. Register
Đăng ký tài khoản mới.

**Endpoint:** `POST /api/auth/register`  
**Auth Required:** No  
**Rate Limit:** Default

**Request Body:**
```json
{
  "name": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Test User",
    "email": "test@example.com"
  },
  "token": "1|abcd1234..."
}
```

---

### 2. Login
Đăng nhập vào hệ thống.

**Endpoint:** `POST /api/auth/login`  
**Auth Required:** No  
**Rate Limit:** Default

**Request Body:**
```json
{
  "email": "test@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Test User",
    "email": "test@example.com"
  },
  "token": "1|abcd1234..."
}
```

---

### 3. Get Current User
Lấy thông tin user hiện tại.

**Endpoint:** `GET /api/auth/user`  
**Auth Required:** Yes  
**Rate Limit:** Default

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "id": 1,
  "name": "Test User",
  "email": "test@example.com",
  "created_at": "2025-01-01T00:00:00.000000Z"
}
```

---

### 4. Update Profile
Cập nhật thông tin profile.

**Endpoint:** `PUT /api/auth/profile`  
**Auth Required:** Yes  
**Rate Limit:** Default

**Request Body:**
```json
{
  "name": "Updated Name",
  "email": "updated@example.com"
}
```

**Response (200):**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Updated Name",
    "email": "updated@example.com"
  }
}
```

---

### 5. Change Password
Đổi mật khẩu.

**Endpoint:** `PUT /api/auth/password`  
**Auth Required:** Yes  
**Rate Limit:** Default

**Request Body:**
```json
{
  "current_password": "password123",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Password changed successfully"
}
```

---

### 6. Upload Avatar
Upload ảnh đại diện.

**Endpoint:** `POST /api/auth/avatar`  
**Auth Required:** Yes  
**Rate Limit:** Default  
**Content-Type:** `multipart/form-data`

**Request Body:**
```
avatar: [file]
```

**Response (200):**
```json
{
  "success": true,
  "avatar_url": "https://lamgame.localhost/storage/avatars/user-1.jpg"
}
```

---

### 7. Get Extended Profile
Lấy thông tin profile mở rộng.

**Endpoint:** `GET /api/auth/profile/extended`  
**Auth Required:** Yes  
**Rate Limit:** Default

**Response (200):**
```json
{
  "id": 1,
  "name": "Test User",
  "email": "test@example.com",
  "bio": "Game developer with 5 years experience",
  "skills": ["Unity", "C#", "3D Modeling"],
  "location": "Ho Chi Minh City",
  "experience_years": 5
}
```

---

### 8. Update Extended Profile
Cập nhật profile mở rộng.

**Endpoint:** `PUT /api/auth/profile/extended`  
**Auth Required:** Yes  
**Rate Limit:** Default

**Request Body:**
```json
{
  "bio": "Game developer with 5 years experience",
  "skills": ["Unity", "C#", "3D Modeling"],
  "location": "Ho Chi Minh City"
}
```

---

### 9. Forgot Password
Gửi link reset password qua email.

**Endpoint:** `POST /api/auth/forgot-password`  
**Auth Required:** No  
**Rate Limit:** Default

**Request Body:**
```json
{
  "email": "test@example.com"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Password reset link sent to your email"
}
```

---

### 10. Reset Password
Reset mật khẩu với token.

**Endpoint:** `POST /api/auth/reset-password`  
**Auth Required:** No  
**Rate Limit:** Default

**Request Body:**
```json
{
  "token": "reset-token-from-email",
  "email": "test@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

---

### 11. Logout
Đăng xuất.

**Endpoint:** `POST /api/auth/logout`  
**Auth Required:** Yes  
**Rate Limit:** Default

**Response (200):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

## Jobs API - Public

### 1. List Jobs
Lấy danh sách công việc (public).

**Endpoint:** `GET /api/jobs`  
**Auth Required:** No  
**Rate Limit:** 60/min

**Query Parameters:**
- `page` (int): Trang hiện tại (default: 1)
- `per_page` (int): Số item mỗi trang (default: 20)
- `status` (int): Trạng thái job (1=active, 0=inactive)
- `sort_by` (string): Sắp xếp theo (created_at, title, salary)
- `sort_order` (string): Thứ tự (asc, desc)
- `location` (string): Lọc theo địa điểm
- `skills` (string): Lọc theo kỹ năng (comma-separated)
- `job_type` (string): Loại công việc (full-time, part-time, contract)

**Example:**
```
GET /api/jobs?page=1&per_page=20&status=1&sort_by=created_at&sort_order=desc
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Unity Developer",
      "slug": "unity-developer",
      "description": "We are looking for...",
      "company_name": "Game Studio ABC",
      "location": "Ho Chi Minh City",
      "salary_range": "1000-2000 USD",
      "job_type": "full-time",
      "experience_level": "mid",
      "skills": ["Unity", "C#", "Git"],
      "benefits": ["Health insurance", "Free lunch"],
      "deadline": "2025-12-31",
      "status": 1,
      "views_count": 150,
      "applications_count": 10,
      "created_at": "2025-01-01T00:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 100,
    "last_page": 5
  }
}
```

---

### 2. Get Job Detail
Lấy chi tiết một công việc.

**Endpoint:** `GET /api/jobs/{id}`  
**Auth Required:** No  
**Rate Limit:** 60/min

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Unity Developer",
    "slug": "unity-developer",
    "description": "We are looking for...",
    "company_name": "Game Studio ABC",
    "location": "Ho Chi Minh City",
    "salary_range": "1000-2000 USD",
    "job_type": "full-time",
    "experience_level": "mid",
    "skills": ["Unity", "C#", "Git"],
    "benefits": ["Health insurance", "Free lunch"],
    "requirements": "- 3+ years Unity experience\n- Good C# skills",
    "responsibilities": "- Develop game features\n- Code review",
    "deadline": "2025-12-31",
    "status": 1,
    "views_count": 150,
    "applications_count": 10,
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z"
  }
}
```

---

### 3. Get Job Categories
Lấy danh sách categories.

**Endpoint:** `GET /api/jobs/categories`  
**Auth Required:** No  
**Rate Limit:** 60/min

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Game Development",
      "slug": "game-development",
      "jobs_count": 50
    },
    {
      "id": 2,
      "name": "Game Design",
      "slug": "game-design",
      "jobs_count": 30
    }
  ]
}
```

---

### 4. Get Job Attributes
Lấy danh sách attributes.

**Endpoint:** `GET /api/jobs/attributes`  
**Auth Required:** No  
**Rate Limit:** 60/min

**Response (200):**
```json
{
  "success": true,
  "data": {
    "job_types": ["full-time", "part-time", "contract", "freelance"],
    "experience_levels": ["junior", "mid", "senior", "lead"],
    "work_modes": ["office", "remote", "hybrid"]
  }
}
```

---

### 5. Apply for Job
Ứng tuyển vào công việc.

**Endpoint:** `POST /api/jobs/{jobId}/apply`  
**Auth Required:** No  
**Rate Limit:** 60/min

**Request Body:**
```json
{
  "name": "Nguyen Van A",
  "email": "applicant@example.com",
  "phone": "0901234567",
  "cv_url": "https://example.com/cv.pdf",
  "cover_letter": "I am very interested in this position..."
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Application submitted successfully",
  "application_id": 123
}
```

---

## Jobs API - Management

⚠️ **Note:** Các endpoints này hiện đang mở (không yêu cầu auth) để test. Production cần enable Sanctum auth.

### 1. Create Job
Tạo công việc mới.

**Endpoint:** `POST /api/jobs`  
**Auth Required:** Should be Yes  
**Rate Limit:** 60/min

**Request Body:**
```json
{
  "title": "Unity Developer",
  "description": "We are looking for an experienced Unity developer...",
  "company_name": "Game Studio ABC",
  "location": "Ho Chi Minh City",
  "salary_range": "1000-2000 USD",
  "job_type": "full-time",
  "experience_level": "mid",
  "skills": ["Unity", "C#", "Git"],
  "benefits": ["Health insurance", "Free lunch"],
  "requirements": "3+ years experience",
  "responsibilities": "Develop game features",
  "deadline": "2025-12-31",
  "status": 1
}
```

---

### 2. Update Job
Cập nhật công việc.

**Endpoint:** `PUT /api/jobs/{id}`  
**Auth Required:** Should be Yes  
**Rate Limit:** 60/min

**Request Body:**
```json
{
  "title": "Senior Unity Developer",
  "description": "Updated description...",
  "salary_range": "2000-3000 USD"
}
```

---

### 3. Delete Job
Xóa công việc.

**Endpoint:** `DELETE /api/jobs/{id}`  
**Auth Required:** Should be Yes  
**Rate Limit:** 60/min

**Response (200):**
```json
{
  "success": true,
  "message": "Job deleted successfully"
}
```

---

### 4. Bulk Create Jobs
Tạo nhiều công việc cùng lúc.

**Endpoint:** `POST /api/jobs/bulk`  
**Auth Required:** Should be Yes  
**Rate Limit:** 60/min

**Request Body:**
```json
{
  "jobs": [
    {
      "title": "Unity Developer",
      "description": "Job 1 description",
      "company_name": "Company A"
    },
    {
      "title": "Unreal Developer",
      "description": "Job 2 description",
      "company_name": "Company B"
    }
  ]
}
```

---

### 5. Publish Job
Publish công việc.

**Endpoint:** `POST /api/jobs/{id}/publish`  
**Auth Required:** Should be Yes  
**Rate Limit:** 60/min

---

### 6. Unpublish Job
Unpublish công việc.

**Endpoint:** `POST /api/jobs/{id}/unpublish`  
**Auth Required:** Should be Yes  
**Rate Limit:** 60/min

---

## Job Options API

### 1. Get Filter Options
Lấy tất cả options cho filter.

**Endpoint:** `GET /api/jobs/options/filter-options`  
**Auth Required:** No  
**Rate Limit:** 120/min

**Response (200):**
```json
{
  "success": true,
  "data": {
    "locations": ["Hanoi", "Ho Chi Minh City", "Da Nang"],
    "skills": ["Unity", "Unreal", "C#", "C++"],
    "job_types": ["full-time", "part-time", "contract"],
    "experience_levels": ["junior", "mid", "senior"],
    "salary_ranges": ["Under 500 USD", "500-1000 USD", "1000-2000 USD"],
    "benefits": ["Health insurance", "Free lunch", "Remote work"]
  }
}
```

---

### 2. Get Form Data
Lấy data cho form tạo job.

**Endpoint:** `GET /api/jobs/options/form-data`  
**Auth Required:** No  
**Rate Limit:** 120/min

---

### 3. Get Locations
Lấy danh sách địa điểm.

**Endpoint:** `GET /api/jobs/options/locations`  
**Auth Required:** No  
**Rate Limit:** 120/min

---

### 4. Get Skills
Lấy danh sách kỹ năng.

**Endpoint:** `GET /api/jobs/options/skills`  
**Auth Required:** No  
**Rate Limit:** 120/min

---

### 5. Get Companies
Lấy danh sách công ty.

**Endpoint:** `GET /api/jobs/options/companies`  
**Auth Required:** No  
**Rate Limit:** 120/min

---

### 6. Get Benefits
Lấy danh sách phúc lợi.

**Endpoint:** `GET /api/jobs/options/benefits`  
**Auth Required:** No  
**Rate Limit:** 120/min

---

### 7. Get Salary Ranges
Lấy danh sách mức lương.

**Endpoint:** `GET /api/jobs/options/salary-ranges`  
**Auth Required:** No  
**Rate Limit:** 120/min

---

### 8. Search Options
Tìm kiếm options.

**Endpoint:** `GET /api/jobs/options/search`  
**Auth Required:** No  
**Rate Limit:** 120/min

**Query Parameters:**
- `q` (string): Từ khóa tìm kiếm
- `type` (string): Loại option (skills, locations, companies)

**Example:**
```
GET /api/jobs/options/search?q=unity&type=skills
```

---

## User Jobs API

🔒 **All endpoints require authentication (Sanctum)**

### 1. Get My Jobs
Lấy danh sách jobs của user.

**Endpoint:** `GET /api/user/jobs`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

**Query Parameters:**
- `page`, `per_page`, `status`, `sort_by`, `sort_order` (tương tự Jobs API)

---

### 2. Create My Job
Tạo job mới.

**Endpoint:** `POST /api/user/jobs`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

---

### 3. Get My Job Detail
Lấy chi tiết job của user.

**Endpoint:** `GET /api/user/jobs/{id}`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

---

### 4. Update My Job
Cập nhật job.

**Endpoint:** `PUT /api/user/jobs/{id}`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

---

### 5. Delete My Job
Xóa job.

**Endpoint:** `DELETE /api/user/jobs/{id}`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

---

### 6. Toggle Job Status
Bật/tắt trạng thái job.

**Endpoint:** `PATCH /api/user/jobs/{id}/toggle-status`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

---

### 7. Get Job Statistics
Lấy thống kê jobs của user.

**Endpoint:** `GET /api/user/jobs/statistics`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

**Response (200):**
```json
{
  "success": true,
  "data": {
    "total_jobs": 50,
    "active_jobs": 30,
    "inactive_jobs": 20,
    "total_applications": 500,
    "total_views": 10000,
    "avg_applications_per_job": 10
  }
}
```

---

### 8. Duplicate Job
Nhân bản job.

**Endpoint:** `POST /api/user/jobs/{id}/duplicate`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

**Request Body:**
```json
{
  "modifications": {
    "title": "Copy of Original Job"
  }
}
```

---

### 9. Extend Deadline
Gia hạn deadline.

**Endpoint:** `POST /api/user/jobs/{id}/extend-deadline`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

**Request Body:**
```json
{
  "new_deadline": "2025-12-31"
}
```

---

### 10. Preview Job
Xem preview job như ứng viên.

**Endpoint:** `GET /api/user/jobs/{id}/preview`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

---

### 11. Boost Job
Đẩy job lên featured/urgent.

**Endpoint:** `POST /api/user/jobs/{id}/boost`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

**Request Body:**
```json
{
  "boost_type": "featured",
  "duration_days": 7
}
```

---

## Dashboard API

🔒 **All endpoints require authentication (Sanctum)**

### 1. Get Dashboard
Lấy tổng quan dashboard.

**Endpoint:** `GET /api/dashboard`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

**Response (200):**
```json
{
  "success": true,
  "data": {
    "newest_jobs": [...],
    "recent_applications": [...],
    "statistics": {
      "total_jobs": 50,
      "active_jobs": 30,
      "total_applications": 500
    }
  }
}
```

---

### 2. Get Job Applications
Lấy danh sách ứng viên của job.

**Endpoint:** `GET /api/dashboard/jobs/{jobId}/applications`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

---

### 3. Update Application Status
Cập nhật trạng thái đơn ứng tuyển.

**Endpoint:** `PUT /api/dashboard/applications/{applicationId}/status`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

**Request Body:**
```json
{
  "status": "accepted"
}
```

---

## Job Analytics API

🔒 **All endpoints require authentication (Sanctum)**

### 1. Get Analytics Overview
Lấy tổng quan analytics.

**Endpoint:** `GET /api/analytics/jobs/overview`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

---

### 2. Get Job Analytics
Lấy analytics của một job.

**Endpoint:** `GET /api/analytics/jobs/{id}/analytics`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

---

### 3. Get Trends
Lấy xu hướng.

**Endpoint:** `GET /api/analytics/jobs/trends`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

**Query Parameters:**
- `period` (string): Khoảng thời gian (7days, 30days, 90days)

---

### 4. Compare Jobs
So sánh nhiều jobs.

**Endpoint:** `POST /api/analytics/jobs/comparison`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

**Request Body:**
```json
{
  "job_ids": [1, 2, 3]
}
```

---

### 5. Get Insights
Lấy insights.

**Endpoint:** `GET /api/analytics/jobs/insights`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

---

## Job Bulk Operations API

🔒 **All endpoints require authentication (Sanctum)**  
**Rate Limit:** 30/min (thấp hơn do operations nặng)

### 1. Bulk Create Jobs
Tạo nhiều jobs cùng lúc.

**Endpoint:** `POST /api/user/jobs/bulk/create`  
**Auth Required:** Yes

---

### 2. Bulk Update Jobs
Cập nhật nhiều jobs.

**Endpoint:** `PUT /api/user/jobs/bulk/update`  
**Auth Required:** Yes

**Request Body:**
```json
{
  "job_ids": [1, 2, 3],
  "updates": {
    "status": 1
  }
}
```

---

### 3. Bulk Delete Jobs
Xóa nhiều jobs.

**Endpoint:** `DELETE /api/user/jobs/bulk/delete`  
**Auth Required:** Yes

---

### 4. Bulk Toggle Status
Bật/tắt nhiều jobs.

**Endpoint:** `PATCH /api/user/jobs/bulk/toggle-status`  
**Auth Required:** Yes

---

### 5. Bulk Archive Jobs
Archive nhiều jobs.

**Endpoint:** `POST /api/user/jobs/bulk/archive`  
**Auth Required:** Yes

---

## Job Import/Export API

🔒 **All endpoints require authentication (Sanctum)**  
**Rate Limit:** 20/min (thấp hơn do file operations)

### 1. Import Jobs
Import jobs từ CSV/Excel.

**Endpoint:** `POST /api/user/jobs/import`  
**Auth Required:** Yes  
**Content-Type:** `multipart/form-data`

**Request Body:**
```
file: [file]
format: csv
```

---

### 2. Export Jobs (GET)
Export jobs ra CSV/Excel.

**Endpoint:** `GET /api/user/jobs/export`  
**Auth Required:** Yes

**Query Parameters:**
- `format` (string): csv, excel, pdf
- `job_ids` (string): Comma-separated IDs

---

### 3. Export Jobs (POST)
Export với filters phức tạp.

**Endpoint:** `POST /api/user/jobs/export`  
**Auth Required:** Yes

**Request Body:**
```json
{
  "format": "excel",
  "job_ids": [1, 2, 3],
  "filters": {
    "status": 1
  }
}
```

---

### 4. Download Import Template
Download template để import.

**Endpoint:** `GET /api/user/jobs/import-template`  
**Auth Required:** Yes

---

### 5. Preview Import
Preview data trước khi import.

**Endpoint:** `POST /api/user/jobs/import-preview`  
**Auth Required:** Yes

---

### 6. Get Import History
Lấy lịch sử import/export.

**Endpoint:** `GET /api/user/jobs/import-history`  
**Auth Required:** Yes

---

## Job Applications API

🔒 **All endpoints require authentication (Sanctum)**

### 1. Get My Applications
Lấy danh sách đơn ứng tuyển của user.

**Endpoint:** `GET /api/applications`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

---

### 2. Get Application Status
Lấy trạng thái đơn ứng tuyển.

**Endpoint:** `GET /api/applications/{applicationId}/status`  
**Auth Required:** Yes  
**Rate Limit:** 60/min

---

## Error Responses

### 400 Bad Request
```json
{
  "success": false,
  "message": "Invalid request data",
  "errors": {
    "title": ["The title field is required."]
  }
}
```

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "You don't have permission to access this resource"
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Resource not found"
}
```

### 422 Unprocessable Entity
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

### 429 Too Many Requests
```json
{
  "success": false,
  "message": "Too many requests. Please try again later."
}
```

### 500 Internal Server Error
```json
{
  "success": false,
  "message": "Internal server error"
}
```

---

## Rate Limiting

| Endpoint Group | Limit |
|---------------|-------|
| Auth API | Default |
| Jobs API (Public) | 60/min |
| Job Options API | 120/min |
| User Jobs API | 60/min |
| Dashboard API | 60/min |
| Analytics API | 60/min |
| Bulk Operations | 30/min |
| Import/Export | 20/min |
| Applications API | 60/min |

---

## Testing với Postman

1. Import file `postman-collection.json` vào Postman
2. Cập nhật biến `base_url` nếu cần (mặc định: `https://lamgame.localhost`)
3. Chạy endpoint "Login" để lấy token
4. Token sẽ tự động được lưu vào biến `api_token`
5. Các endpoint protected sẽ tự động sử dụng token này

---

## Notes

- ⚠️ Các endpoints trong "Jobs API - Management" hiện đang mở để test, cần enable Sanctum auth khi deploy production
- 🔒 Tất cả User Jobs API, Dashboard API, Analytics API, Bulk Operations API, Import/Export API và Applications API đều yêu cầu authentication
- Rate limiting được áp dụng khác nhau tùy endpoint group
- Bulk operations và Import/Export có rate limit thấp hơn do tính chất nặng của operations

---

**Last Updated:** 2025-10-28  
**Version:** 1.0
