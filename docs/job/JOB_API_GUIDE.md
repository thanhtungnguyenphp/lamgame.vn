# Job Management API Guide

> Cập nhật: 2026-05-06 | Dựa trên code thực tế

## Tổng quan

API quản lý tuyển dụng cho platform LamGame.

- **Base URL:** `/api/manage/`
- **Auth:** Header `X-Api-Key: {admin_api_token}`
- **Rate limit:** Read 60/min, Write 10/min
- **Route file:** `routes/api-job-manage.php`
- **Controllers:** `JobManageController`, `CandidateManageController`, `CompanyManageController`
- **Services:** `JobPostingService`, `JobPostingApplicationService`

## Endpoints Summary (15 total)

| # | Method | Endpoint | Description |
|---|--------|----------|-------------|
| 1 | GET | /jobs | Danh sách jobs |
| 2 | GET | /jobs/statistics | Thống kê |
| 3 | GET | /jobs/{slug} | Chi tiết job |
| 4 | POST | /jobs | Tạo job |
| 5 | PUT | /jobs/{slug} | Cập nhật job |
| 6 | DELETE | /jobs/{slug} | Xóa job |
| 7 | POST | /jobs/{slug}/status | Đổi trạng thái |
| 8 | GET | /candidates | Danh sách ứng viên |
| 9 | GET | /candidates/statistics | Thống kê ứng viên |
| 10 | GET | /candidates/{id} | Chi tiết đơn |
| 11 | PATCH | /candidates/{id}/status | Cập nhật trạng thái |
| 12 | DELETE | /candidates/{id} | Xóa đơn |
| 13 | GET | /companies | Danh sách công ty |
| 14 | GET | /companies/{id} | Chi tiết công ty |
| 15 | POST | /companies | Tạo công ty |
| 16 | POST | /companies/{id} | Cập nhật công ty |
| 17 | DELETE | /companies/{id} | Xóa công ty |

**Note:** Tất cả endpoints tự động scope theo `auth_admin.id` (chỉ thấy data của mình).

---

## 1. Jobs

### GET /api/manage/jobs

**Query params:**
| Param | Type | Description |
|-------|------|-------------|
| search | string | Tìm kiếm |
| job_type | string | Loại công việc |
| location | string | Địa điểm |
| experience_level | string | Cấp độ |
| is_featured | bool | Tin nổi bật |
| is_remote | bool | Remote |
| status | string | draft, active, paused, archived |
| sort_by | string | Trường sắp xếp |
| sort_dir | string | asc, desc |
| per_page | int | Default: 15 |

### GET /api/manage/jobs/statistics

Thống kê jobs của admin hiện tại (total, by status, etc.).

### GET /api/manage/jobs/{slug}

Chi tiết job posting (lookup by slug). Trả về `JobPostingResource`.

### POST /api/manage/jobs

Tạo tin tuyển dụng.

```json
{
  "title": "required|string|max:255",
  "description": "required|string",
  "short_description": "nullable|string|max:500",
  "job_type": "nullable|string|max:50",
  "experience_level": "nullable|string|max:50",
  "salary_range": "nullable|string",
  "salary_min": "nullable|numeric|min:0",
  "salary_max": "nullable|numeric|gte:salary_min",
  "location": "nullable|string",
  "is_remote": "nullable|boolean",
  "education_level": "nullable|string|max:50",
  "english_level": "nullable|string|max:50",
  "company_name": "nullable|string|max:255",
  "company_id": "nullable|int|exists:companies,id",
  "company_size": "nullable|string|max:50",
  "contact_email": "nullable|email",
  "contact_phone": "nullable|string|max:20",
  "application_method": "nullable|string",
  "application_url": "nullable|url",
  "application_deadline": "nullable|date|after:today",
  "is_featured": "nullable|boolean",
  "is_urgent": "nullable|boolean",
  "status": "nullable|in:draft,active",
  "skills": "nullable|array|max:20",
  "skills.*": "string|max:100",
  "benefits": "nullable|array|max:20",
  "benefits.*": "string|max:100",
  "meta_title": "nullable|string|max:255",
  "meta_description": "nullable|string|max:500"
}
```

### PUT /api/manage/jobs/{slug}

Cập nhật job. Tất cả fields optional. Status cho phép: `draft, active, paused, archived`.

### DELETE /api/manage/jobs/{slug}

Xóa job posting.

### POST /api/manage/jobs/{slug}/status

```json
{ "status": "required|in:draft,active,paused,archived" }
```

**Logic:**
- `active` → gọi `service->publish()` (set published_at, etc.)
- `paused` → gọi `service->unpublish()`
- `draft`, `archived` → update trực tiếp

---

## 2. Candidates

### GET /api/manage/candidates

**Query params:**
| Param | Type | Description |
|-------|------|-------------|
| job_posting_id | int | Lọc theo job cụ thể |
| status | string | pending, reviewed, shortlisted, accepted, rejected |
| search | string | Tìm theo applicant_name/email |
| per_page | int | Default: 15 |

Sorted by `applied_at` DESC.

### GET /api/manage/candidates/statistics

**Query params:** `job_posting_id` (optional).

Nếu không có job_posting_id → tổng hợp tất cả jobs của admin.

```json
{
  "total": 50,
  "pending": 20,
  "reviewed": 10,
  "shortlisted": 8,
  "accepted": 7,
  "rejected": 5
}
```

### GET /api/manage/candidates/{id}

Chi tiết đơn ứng tuyển + job posting info (title, slug, company_name).

### PATCH /api/manage/candidates/{id}/status

```json
{
  "status": "required|in:pending,reviewed,shortlisted,accepted,rejected",
  "notes": "nullable|string|max:2000"
}
```

### DELETE /api/manage/candidates/{id}

Xóa đơn ứng tuyển.

---

## 3. Companies

### GET /api/manage/companies

**Query params:** search (name/industry), per_page.

### GET /api/manage/companies/{id}

Chi tiết công ty.

### POST /api/manage/companies

Tạo công ty mới. **Content-Type: multipart/form-data** (hỗ trợ upload logo).

```json
{
  "name": "required|string|max:255",
  "description": "nullable|string",
  "website": "nullable|url",
  "email": "nullable|email",
  "phone": "nullable|string|max:20",
  "address": "nullable|string|max:500",
  "employee_count": "nullable|int|min:1",
  "founded_year": "nullable|int|min:1900|max:2026",
  "industry": "nullable|string|max:100",
  "logo": "nullable|file|mimes:jpg,jpeg,png,webp,svg|max:2048"
}
```

### POST /api/manage/companies/{id}

Cập nhật công ty (POST thay vì PUT vì hỗ trợ file upload). Logo cũ sẽ bị xóa khi upload mới.

### DELETE /api/manage/companies/{id}

Xóa công ty.

---

## Enums

### Job Statuses
- `draft` - Nháp
- `active` - Đang tuyển
- `paused` - Tạm dừng
- `archived` - Lưu trữ

### Candidate Statuses
- `pending` - Chờ xem
- `reviewed` - Đã xem
- `shortlisted` - Vào danh sách ngắn
- `accepted` - Chấp nhận
- `rejected` - Từ chối

### Candidate Status Flow
```
pending → reviewed → shortlisted → accepted
                                  → rejected
```

---

## Database Tables

- `job_postings` - Tin tuyển dụng (lookup by slug)
- `job_posting_skills` - Skills (one-to-many)
- `job_posting_benefits` - Benefits (one-to-many)
- `job_applications` - Đơn ứng tuyển
- `companies` - Công ty (scoped by created_by_admin_id)

---

## Public Job API V2

Ngoài Management API, còn có Public API tại `routes/api-job-v2.php`:

- **Prefix:** `/api/v2/jobs/`
- **Auth:** Không cần (public)
- **Endpoints:** Listing, detail, apply, filters

(Xem chi tiết trong route file `api-job-v2.php`)
