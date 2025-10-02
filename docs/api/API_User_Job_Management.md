# API User Job Management

Tài liệu này mô tả các endpoints API cho phép người dùng đã đăng nhập quản lý bài tuyển dụng của riêng họ.

## Xác thực

Tất cả endpoints yêu cầu xác thực qua Sanctum token:

```http
Authorization: Bearer {your-sanctum-token}
```

## Base URL

```
POST   /api/user/jobs
GET    /api/user/jobs
GET    /api/user/jobs/{id}
PUT    /api/user/jobs/{id}
DELETE /api/user/jobs/{id}
PATCH  /api/user/jobs/{id}/toggle-status
```

## Rate Limiting

Tất cả endpoints được giới hạn **60 requests/phút** theo IP.

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

#### Response Success (200)

```json
{
  "success": true,
  "message": "Jobs retrieved successfully",
  "data": [
    {
      "id": 123,
      "sku": "USER_1_TECHCORP_DEVELOPER_2024",
      "title": "Senior PHP Developer",
      "company_name": "TechCorp",
      "description": "We are looking for...",
      "short_description": "Senior developer position",
      "salary_range": "20-30 triệu",
      "job_location": "Ho Chi Minh City",
      "job_type": "full-time",
      "experience_level": "senior",
      "status": "active",
      "is_urgent": false,
      "is_featured": false,
      "application_deadline": "2024-02-15",
      "contact_email": "hr@techcorp.com",
      "contact_phone": "0901234567",
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:30:00Z"
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

#### Request Body

```json
{
  "title": "Senior PHP Developer",
  "company_name": "TechCorp Vietnam",
  "description": "We are looking for an experienced PHP developer to join our growing team. The ideal candidate will have strong experience with Laravel framework and modern development practices.",
  "short_description": "Senior developer position with Laravel expertise required",
  "job_type": "full-time",
  "experience_level": "senior", 
  "salary_range": "20-30 triệu",
  "job_location": "Ho Chi Minh City",
  "company_size": "51-200",
  "required_skills": ["php", "laravel", "mysql", "git"],
  "education_level": "university",
  "english_level": "intermediate",
  "job_benefits": ["health-insurance", "13th-salary", "flexible-hours"],
  "application_deadline": "2024-02-15",
  "contact_email": "hr@techcorp.com",
  "contact_phone": "0901234567",
  "company_website": "https://techcorp.com",
  "is_urgent": false,
  "is_featured": false,
  "application_method": "email",
  "status": true
}
```

#### Field Validation

| Field | Type | Required | Validation |
|-------|------|----------|------------|
| `title` | string | Yes | max:255 |
| `company_name` | string | Yes | max:255 |
| `description` | string | Yes | min:50 |
| `short_description` | string | Yes | max:500 |
| `job_type` | string | No | in:full-time,part-time,contract,intern |
| `experience_level` | string | No | in:entry,junior,mid,senior,lead |
| `salary_range` | string | No | max:100 |
| `job_location` | string | No | max:255 |
| `company_size` | string | No | in:1-10,11-50,51-200,201-500,500+ |
| `required_skills` | array | No | array of strings |
| `education_level` | string | No | in:high-school,college,university,master,phd |
| `english_level` | string | No | in:basic,intermediate,advanced,native |
| `job_benefits` | array | No | array of strings |
| `application_deadline` | date | No | date format, future date |
| `contact_email` | email | No | valid email |
| `contact_phone` | string | No | regex phone format |
| `company_website` | url | No | valid URL |
| `is_urgent` | boolean | No | |
| `is_featured` | boolean | No | |
| `application_method` | string | No | in:email,phone,website,in-person |
| `status` | boolean | No | default: true |

#### Response Success (201)

```json
{
  "success": true,
  "message": "Job created successfully",
  "data": {
    "id": 124,
    "sku": "USER_1_TECHCORP_VIETNAM_SENIOR_PHP_DEVELOPER_2024",
    "title": "Senior PHP Developer",
    "company_name": "TechCorp Vietnam",
    // ... all job fields
    "created_at": "2024-01-15T14:20:00Z",
    "updated_at": "2024-01-15T14:20:00Z"
  }
}
```

#### Response Error (422)

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."],
    "contact_email": ["The contact email must be a valid email address."]
  }
}
```

---

## 3. Lấy chi tiết một job

### `GET /api/user/jobs/{id}`

Lấy chi tiết một bài tuyển dụng cụ thể thuộc về user hiện tại.

#### Response Success (200)

```json
{
  "success": true,
  "message": "Job retrieved successfully",
  "data": {
    "id": 123,
    "sku": "USER_1_TECHCORP_DEVELOPER_2024",
    "title": "Senior PHP Developer",
    // ... all job fields
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z"
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

#### Response Success (200)

```json
{
  "success": true,
  "message": "Job updated successfully", 
  "data": {
    "id": 123,
    // ... updated job data
    "updated_at": "2024-01-15T16:45:00Z"
  }
}
```

---

## 5. Xóa job

### `DELETE /api/user/jobs/{id}`

Xóa bài tuyển dụng thuộc về user hiện tại.

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

## Notes

1. **Quyền sở hữu**: User chỉ có thể thao tác với các bài tuyển dụng do chính họ tạo (`created_by_admin_id` = user ID).

2. **SKU Generation**: System tự động tạo SKU unique cho mỗi job theo format: `USER_{userId}_{company}_{title}_{year}`

3. **Category Assignment**: Tất cả jobs được tự động gán vào category "Việc Làm" (ID: 102).

4. **Logging**: Tất cả thao tác quan trọng (tạo, sửa, xóa) đều được log để audit.

5. **Validation**: Sử dụng `CreateUserJobRequest` để validate dữ liệu đầu vào.

6. **Error Handling**: Tất cả lỗi đều được handle và trả về response nhất quán.

## Ví dụ sử dụng với cURL

### Tạo job mới

```bash
curl -X POST https://lamgame.localhost/api/user/jobs \
  -H "Authorization: Bearer your-sanctum-token" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Senior PHP Developer",
    "company_name": "TechCorp Vietnam", 
    "description": "We are looking for an experienced developer...",
    "short_description": "Senior developer position",
    "job_type": "full-time",
    "experience_level": "senior",
    "salary_range": "20-30 triệu",
    "contact_email": "hr@techcorp.com"
  }'
```

### Lấy danh sách jobs

```bash
curl -X GET "https://lamgame.localhost/api/user/jobs?search=developer&status=active&per_page=10" \
  -H "Authorization: Bearer your-sanctum-token"
```

### Toggle trạng thái job

```bash
curl -X PATCH https://lamgame.localhost/api/user/jobs/123/toggle-status \
  -H "Authorization: Bearer your-sanctum-token"
```