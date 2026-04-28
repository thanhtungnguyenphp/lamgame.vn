# Job Management API — Hướng dẫn tích hợp Ohha Studio

> Cập nhật: 28/04/2026
> Base URL: `https://lamgame.vn/api`
> Pattern: Tương tự Blog Publish API

---

## Tổng quan

Hệ thống Job có 2 tầng API:

| Tầng | Prefix | Auth | Mục đích |
|-------|--------|------|----------|
| **Management API** | `/api/manage/` | `X-Api-Key` header | Ohha Studio — quản lý jobs, candidates, companies |
| **Public API V2** | `/api/v2/jobs/` | Public + Sanctum | Frontend web — listing, detail, apply |

**Ohha Studio sử dụng Management API** (giống Blog Publish API).

---

## Authentication

```
Header: X-Api-Key: {admin_api_token}
```

Tất cả endpoints Management API yêu cầu header `X-Api-Key`. Token lấy từ bảng `admins.api_token`.

---

## Response Format

Tất cả response trả về JSON:

```json
{
  "status": "success",
  "message": "Mô tả kết quả",
  "data": { ... },
  "meta": { "current_page": 1, "last_page": 5, "per_page": 15, "total": 72 }
}
```

Error response:
```json
{
  "status": "error",
  "message": "Mô tả lỗi"
}
```

HTTP Status Codes: `200` OK, `201` Created, `404` Not Found, `409` Conflict, `422` Validation Error, `429` Too Many Requests.

---

## 1. JOB MANAGEMENT

### 1.1 Danh sách Jobs

```
GET /api/manage/jobs
```

**Query Parameters:**

| Param | Type | Default | Mô tả |
|-------|------|---------|--------|
| `search` | string | — | Tìm theo title, description, company_name |
| `status` | string | — | `draft`, `active`, `paused`, `archived` |
| `job_type` | string | — | `full-time`, `part-time`, `contract`, `freelance`, `internship` |
| `location` | string | — | Tìm theo location (LIKE) |
| `experience_level` | string | — | `junior`, `mid`, `senior`, `lead`, `manager` |
| `is_featured` | boolean | — | Lọc job nổi bật |
| `is_remote` | boolean | — | Lọc job remote |
| `sort_by` | string | `created_at` | `created_at`, `title`, `salary_max`, `view_count`, `application_count` |
| `sort_dir` | string | `desc` | `asc`, `desc` |
| `per_page` | int | 15 | 1–100 |

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "title": "Unity Developer",
      "slug": "unity-developer",
      "short_description": "...",
      "job_type": "full-time",
      "experience_level": "mid",
      "salary_min": 15000000,
      "salary_max": 25000000,
      "salary_currency": "VND",
      "location": "Hồ Chí Minh",
      "is_remote": false,
      "company_name": "GameStudio VN",
      "status": "active",
      "is_featured": false,
      "is_urgent": false,
      "view_count": 150,
      "application_count": 12,
      "application_deadline": "2026-06-30",
      "published_at": "2026-04-25T10:00:00+07:00",
      "created_at": "2026-04-24T09:00:00+07:00"
    }
  ],
  "meta": { "current_page": 1, "last_page": 3, "per_page": 15, "total": 42 }
}
```

**Rate limit:** 60 requests/phút

---

### 1.2 Chi tiết Job

```
GET /api/manage/jobs/{slug}
```

**Response:** Full `JobPostingResource`:
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "title": "Unity Developer",
    "slug": "unity-developer",
    "description": "<p>Mô tả chi tiết...</p>",
    "short_description": "Tuyển Unity Dev...",
    "url": "/viec-lam/unity-developer",

    "job_type": "full-time",
    "experience_level": "mid",
    "salary_range": "15-25 triệu",
    "salary_min": 15000000,
    "salary_max": 25000000,
    "salary_currency": "VND",
    "location": "Hồ Chí Minh",
    "is_remote": false,

    "education_level": "Đại học",
    "english_level": "Giao tiếp",
    "skills": ["Unity", "C#", "3D Math"],
    "benefits": ["Lương tháng 13", "Bảo hiểm"],

    "company_name": "GameStudio VN",
    "company_size": "50-100",
    "company_logo": "https://lamgame.vn/storage/company-logos/gamestudio.png",

    "contact_email": "hr@gamestudio.vn",
    "contact_phone": "0901234567",
    "application_method": "email",
    "application_url": null,

    "status": "active",
    "is_featured": false,
    "is_urgent": false,
    "application_deadline": "2026-06-30",
    "days_remaining": 63,
    "is_expired": false,

    "view_count": 150,
    "application_count": 12,
    "click_count": 45,

    "meta_title": "Tuyển Unity Developer - GameStudio VN",
    "meta_description": "...",
    "meta_keywords": "unity, game developer, hcm",

    "published_at": "2026-04-25T10:00:00+07:00",
    "created_at": "2026-04-24T09:00:00+07:00",
    "updated_at": "2026-04-26T14:30:00+07:00"
  }
}
```

---

### 1.3 Tạo Job mới

```
POST /api/manage/jobs
```

**Body (JSON):**

| Field | Type | Required | Mô tả |
|-------|------|:--------:|--------|
| `title` | string | ✅ | Tiêu đề (max 255) |
| `description` | string | ✅ | Mô tả HTML |
| `short_description` | string | — | Tóm tắt (max 500) |
| `job_type` | string | — | `full-time`, `part-time`, `contract`, `freelance`, `internship` |
| `experience_level` | string | — | `junior`, `mid`, `senior`, `lead`, `manager` |
| `salary_range` | string | — | Text hiển thị (VD: "15-25 triệu") |
| `salary_min` | number | — | Lương tối thiểu |
| `salary_max` | number | — | Lương tối đa (≥ salary_min) |
| `salary_currency` | string | — | Default: `VND` |
| `location` | string | — | Địa điểm |
| `is_remote` | boolean | — | Cho phép remote |
| `education_level` | string | — | Yêu cầu học vấn |
| `english_level` | string | — | Yêu cầu tiếng Anh |
| `company_name` | string | — | Tên công ty |
| `company_size` | string | — | Quy mô (VD: "50-100") |
| `contact_email` | email | — | Email liên hệ |
| `contact_phone` | string | — | SĐT liên hệ |
| `application_method` | string | — | `email`, `url`, `both` |
| `application_url` | url | — | Link ứng tuyển ngoài |
| `application_deadline` | date | — | Hạn ứng tuyển (YYYY-MM-DD, phải > hôm nay) |
| `is_featured` | boolean | — | Job nổi bật |
| `is_urgent` | boolean | — | Job gấp |
| `status` | string | — | `draft` (default) hoặc `active` |
| `skills` | array | — | Danh sách kỹ năng (max 20, mỗi item max 100 ký tự) |
| `benefits` | array | — | Danh sách phúc lợi (max 20) |
| `meta_title` | string | — | SEO title |
| `meta_description` | string | — | SEO description |

**Response:** `201 Created`
```json
{
  "status": "success",
  "message": "Job posting created successfully",
  "data": { "id": 15, "slug": "unity-developer-1", ... }
}
```

**Rate limit:** 10 requests/phút

---

### 1.4 Cập nhật Job

```
PUT /api/manage/jobs/{slug}
```

Body giống tạo mới, chỉ gửi fields cần update. `title` và `description` dùng `sometimes` (không bắt buộc khi update).

**Response:** `200 OK` với data đã cập nhật.

**Rate limit:** 10 requests/phút

---

### 1.5 Xóa Job

```
DELETE /api/manage/jobs/{slug}
```

**Response:**
```json
{
  "status": "success",
  "message": "Job posting deleted successfully"
}
```

**Rate limit:** 10 requests/phút

---

### 1.6 Đổi trạng thái Job

```
POST /api/manage/jobs/{slug}/status
```

**Body:**
```json
{
  "status": "active"
}
```

Giá trị hợp lệ: `draft`, `active`, `paused`, `archived`

**Workflow:**
```
draft → active → paused → archived
              ↘ archived
       paused → active
```

**Rate limit:** 10 requests/phút

---

### 1.7 Thống kê Jobs

```
GET /api/manage/jobs/statistics
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "total": 42,
    "active": 30,
    "draft": 5,
    "paused": 3,
    "archived": 4,
    "total_views": 5200,
    "total_applications": 180
  }
}
```

---

## 2. CANDIDATE / APPLICATION MANAGEMENT

### 2.1 Danh sách ứng viên

```
GET /api/manage/candidates
```

**Query Parameters:**

| Param | Type | Mô tả |
|-------|------|--------|
| `job_posting_id` | int | Lọc theo job cụ thể |
| `status` | string | `pending`, `reviewed`, `shortlisted`, `accepted`, `rejected` |
| `search` | string | Tìm theo tên hoặc email |
| `per_page` | int | Default 15 |

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "application_code": "APP-001-ABC",
      "status": "pending",
      "applicant_name": "Nguyễn Văn A",
      "applicant_email": "a@email.com",
      "applicant_phone": "0901234567",
      "cover_letter": "...",
      "resume_file_path": "/storage/resumes/abc.pdf",
      "additional_info": null,
      "employer_notes": null,
      "job": {
        "id": 1,
        "title": "Unity Developer",
        "slug": "unity-developer",
        "company_name": "GameStudio VN"
      },
      "applied_at": "2026-04-26T10:00:00+07:00",
      "created_at": "2026-04-26T10:00:00+07:00"
    }
  ],
  "meta": { "current_page": 1, "total": 25 }
}
```

> Chỉ trả về ứng viên của jobs do admin hiện tại tạo.

---

### 2.2 Chi tiết ứng viên

```
GET /api/manage/candidates/{id}
```

**Response:** Full `JobApplicationResource`.

---

### 2.3 Cập nhật trạng thái ứng viên

```
PATCH /api/manage/candidates/{id}/status
```

**Body:**
```json
{
  "status": "shortlisted",
  "notes": "Ứng viên có kinh nghiệm Unity 3 năm"
}
```

Giá trị hợp lệ: `pending`, `reviewed`, `shortlisted`, `accepted`, `rejected`

**Workflow:**
```
pending → reviewed → shortlisted → accepted
                                  → rejected
         reviewed → rejected
```

**Rate limit:** 10 requests/phút

---

### 2.4 Thống kê ứng viên

```
GET /api/manage/candidates/statistics
```

**Query:** `?job_posting_id=1` (optional — nếu không truyền, thống kê tất cả jobs)

**Response:**
```json
{
  "status": "success",
  "data": {
    "total": 25,
    "pending": 10,
    "reviewed": 5,
    "shortlisted": 6,
    "accepted": 3,
    "rejected": 1
  }
}
```

---

### 2.5 Xóa ứng viên

```
DELETE /api/manage/candidates/{id}
```

---

## 3. COMPANY MANAGEMENT

### 3.1 Danh sách công ty

```
GET /api/manage/companies
```

**Query:** `search` (tìm theo name/industry), `per_page`

**Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "GameStudio VN",
      "description": "...",
      "logo_url": "https://lamgame.vn/storage/company-logos/gamestudio.png",
      "website": "https://gamestudio.vn",
      "email": "hr@gamestudio.vn",
      "phone": "0901234567",
      "address": "123 Nguyễn Huệ, Q1, HCM",
      "employee_count": 80,
      "founded_year": 2018,
      "industry": "Game Development",
      "status": "active",
      "created_at": "2026-04-24T09:00:00+07:00"
    }
  ]
}
```

---

### 3.2 Chi tiết công ty

```
GET /api/manage/companies/{id}
```

---

### 3.3 Tạo công ty

```
POST /api/manage/companies
```

**Body (multipart/form-data):**

| Field | Type | Required | Mô tả |
|-------|------|:--------:|--------|
| `name` | string | ✅ | Tên công ty (max 255) |
| `description` | string | — | Mô tả |
| `logo` | file | — | Logo (image, max 2MB) |
| `website` | url | — | Website |
| `email` | email | — | Email |
| `phone` | string | — | SĐT |
| `address` | string | — | Địa chỉ |
| `employee_count` | int | — | Số nhân viên |
| `founded_year` | int | — | Năm thành lập |
| `industry` | string | — | Ngành nghề |

---

### 3.4 Cập nhật công ty

```
POST /api/manage/companies/{id}
```

> Dùng POST (không phải PUT) vì hỗ trợ file upload. Gửi `_method=PUT` nếu cần.

---

### 3.5 Xóa công ty

```
DELETE /api/manage/companies/{id}
```

---

## 4. PUBLIC API (Frontend)

Dành cho web frontend, không cần auth cho read endpoints.

### 4.1 Danh sách Jobs (public)

```
GET /api/v2/jobs
```

Query: `search`, `job_type`, `location`, `experience_level`, `is_remote`, `is_featured`, `sort_by`, `sort_dir`, `per_page`

Chỉ trả về jobs có `status=active` và chưa hết hạn.

---

### 4.2 Filter Options

```
GET /api/v2/jobs/filters
```

Trả về danh sách distinct values cho dropdown (cached 1 giờ):
```json
{
  "job_types": ["full-time", "part-time", "contract"],
  "experience_levels": ["junior", "mid", "senior"],
  "locations": ["Hồ Chí Minh", "Hà Nội", "Đà Nẵng"],
  "education_levels": ["Đại học", "Cao đẳng"],
  "english_levels": ["Giao tiếp", "Đọc hiểu"],
  "company_sizes": ["10-50", "50-100", "100-500"],
  "salary_currencies": ["VND", "USD"],
  "skills": ["Unity", "C#", "Unreal Engine"]
}
```

---

### 4.3 Chi tiết Job (public)

```
GET /api/v2/jobs/{id}
GET /api/v2/jobs/slug/{slug}
```

Tự động tăng view_count (session-based dedup).

---

### 4.4 Ứng tuyển

```
POST /api/v2/jobs/{id}/apply
```

**Body (multipart/form-data):**

| Field | Type | Required | Mô tả |
|-------|------|:--------:|--------|
| `applicant_name` | string | ✅ | Họ tên (2-255 ký tự) |
| `applicant_email` | email | ✅ | Email |
| `applicant_phone` | string | — | SĐT |
| `resume` | file | — | CV (PDF/DOC/DOCX, max 5MB) |
| `cover_letter` | string | — | Thư xin việc (max 5000) |
| `additional_info` | object | — | Thông tin bổ sung |

**Response:** `201 Created`
```json
{
  "status": "success",
  "message": "Ứng tuyển thành công!",
  "data": {
    "application_code": "APP-001-XYZ",
    "status": "pending"
  }
}
```

Trùng email+job → `409 Conflict`.

**Rate limit:** 5 requests/60 phút

---

### 4.5 Tra cứu đơn ứng tuyển (auth:sanctum)

```
GET /api/v2/applications
GET /api/v2/applications/{id}/status
```

---

## 5. SO SÁNH VỚI BLOG API

| Tính năng | Blog API | Job Management API |
|-----------|----------|-------------------|
| Auth | `X-Api-Key` | `X-Api-Key` (giống) |
| Prefix | `/api/blog/` | `/api/manage/jobs/` |
| Resource ID | slug | slug (jobs), id (candidates, companies) |
| CRUD | publish/update/delete | publish/update/destroy |
| Status | draft→scheduled→published→archived | draft→active→paused→archived |
| List | `/blog/list` | `/manage/jobs` |
| Detail | `/blog/detail/{slug}` | `/manage/jobs/{slug}` |
| Create | `POST /blog/publish` | `POST /manage/jobs` |
| Update | `POST /blog/update/{slug}` | `PUT /manage/jobs/{slug}` |
| Delete | `DELETE /blog/delete/{slug}` | `DELETE /manage/jobs/{slug}` |
| Change status | `POST /blog/status/{slug}` | `POST /manage/jobs/{slug}/status` |
| Extra | — | Candidates, Companies, Statistics |

---

## 6. JOB CRAWLER

Hệ thống tự động crawl jobs từ nguồn bên ngoài.

**Artisan command:**
```bash
php artisan job:crawl --source=topdev --keyword="unity" --limit=20 --sync
php artisan job:crawl --category=dev --limit=50
php artisan job:crawl --category=all
```

**Categories:** `dev`, `art`, `qc`, `content`, `general`, `all`

**Config:** `config/job_crawler.php`
- Sources: TopDev (mở rộng thêm sau)
- auto_publish: `false` (admin review trước khi publish)
- delay: 3s giữa các request
- max_per_run: 50

**Flow:** Crawl → Normalize → Dedup → Save (status=draft) → Admin review → Publish

---

## 7. KIẾN TRÚC CODE

```
app/
├── Http/Controllers/
│   ├── Admin/JobPostingController.php      # Admin web panel
│   └── Api/
│       ├── JobPostingController.php        # Public V2 API
│       ├── JobManageController.php         # Management API (Ohha Studio)
│       ├── JobPostingApplicationController.php  # Apply + tracking
│       ├── CandidateManageController.php   # Candidate management
│       └── CompanyManageController.php     # Company management
├── Http/Requests/
│   ├── StoreJobPostingRequest.php
│   ├── UpdateJobPostingRequest.php
│   └── Api/JobApplicationRequest.php
├── Http/Resources/
│   ├── JobPostingResource.php
│   ├── JobApplicationResource.php
│   └── CompanyResource.php
├── Models/
│   ├── JobPosting.php                      # 30+ fields, SoftDeletes
│   ├── JobPostingSkill.php
│   ├── JobPostingBenefit.php
│   ├── JobApplication.php
│   └── JobCrawlLog.php
├── Services/
│   ├── JobPostingService.php               # Core CRUD + business logic
│   ├── JobPostingApplicationService.php    # Apply + notifications
│   └── JobCrawler/
│       ├── JobCrawlerService.php           # Orchestrator
│       ├── JobNormalizer.php               # Data normalization
│       ├── DuplicateDetector.php           # Dedup logic
│       └── Sources/
│           ├── CrawlerSourceInterface.php
│           └── TopDevCrawler.php
routes/
├── api-job-manage.php                      # Management API routes
├── api-job-v2.php                          # Public V2 API routes
└── admin.php                               # Admin web routes
```
