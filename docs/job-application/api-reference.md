# API Reference - Job Application

## Base URL

```
https://lamgame.localhost/api
```

---

## Endpoints

### 1. Submit Job Application

Nộp đơn ứng tuyển cho một vị trí việc làm.

**Endpoint:** `POST /jobs/{jobId}/apply`

**Authentication:** Optional (hỗ trợ cả guest và authenticated users)

**Headers:**
```http
Content-Type: multipart/form-data
Accept: application/json
X-CSRF-TOKEN: {csrf_token}
X-Requested-With: XMLHttpRequest
```

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `jobId` | integer | Yes | ID của job (từ bảng products) |

**Body Parameters:**

| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| `full_name` | string | Yes | min:2, max:100, regex | Họ và tên đầy đủ |
| `email` | string | Yes | email:rfc,dns, max:255 | Email liên hệ |
| `phone` | string | Yes | regex (VN format) | Số điện thoại Việt Nam |
| `cv` | file | Yes | pdf/doc/docx, max:5MB | File CV |
| `cover_letter` | string | No | max:2000 | Thư giới thiệu |
| `experience` | string | No | in:fresher,junior,middle,senior | Mức kinh nghiệm |

**Phone Format:**
```
Valid: 0936666809, +84936666809, 84936666809
Pattern: ^(\+84|84|0)(3[2-9]|5[6|8|9]|7[0|6-9]|8[1-9]|9[0-9])[0-9]{7}$
```

**Request Example (cURL):**

```bash
curl -X POST https://lamgame.localhost/api/jobs/36/apply \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -F "full_name=Nguyễn Văn A" \
  -F "email=user@example.com" \
  -F "phone=0936666809" \
  -F "cv=@/path/to/cv.pdf" \
  -F "cover_letter=Tôi rất quan tâm đến vị trí này..." \
  -F "experience=junior"
```

**Request Example (JavaScript):**

```javascript
const formData = new FormData();
formData.append('full_name', 'Nguyễn Văn A');
formData.append('email', 'user@example.com');
formData.append('phone', '0936666809');
formData.append('cv', fileInput.files[0]);
formData.append('cover_letter', 'Tôi rất quan tâm...');
formData.append('experience', 'junior');

fetch('/api/jobs/36/apply', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    },
    body: formData
})
.then(response => response.json())
.then(data => console.log(data));
```

**Success Response (201 Created):**

```json
{
    "success": true,
    "message": "Hồ sơ ứng tuyển đã được gửi thành công!",
    "data": {
        "application_id": 123,
        "application_code": "JA-20251209-36-A1B2",
        "status": "pending",
        "applied_at": "09/12/2025 17:30",
        "job_title": "Senior Game Developer",
        "message": "Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất."
    }
}
```

**Error Responses:**

**404 Not Found - Job không tồn tại:**
```json
{
    "success": false,
    "message": "Không tìm thấy công việc này",
    "error": "JOB_NOT_FOUND"
}
```

**409 Conflict - Đã ứng tuyển:**
```json
{
    "success": false,
    "message": "Bạn đã ứng tuyển vị trí này rồi",
    "error": "DUPLICATE_APPLICATION",
    "existing_application": {
        "applied_at": "08/12/2025 10:00",
        "status": "pending"
    }
}
```

**422 Unprocessable Entity - Validation Error:**
```json
{
    "success": false,
    "message": "Dữ liệu không hợp lệ",
    "error": "VALIDATION_ERROR",
    "errors": {
        "full_name": ["Họ và tên phải có ít nhất 2 ký tự"],
        "email": ["Email không đúng định dạng"],
        "phone": ["Số điện thoại không đúng định dạng Việt Nam"],
        "cv": ["Vui lòng tải lên file CV"]
    },
    "details": {
        "full_name": {
            "field": "full_name",
            "messages": ["Họ và tên phải có ít nhất 2 ký tự"],
            "value": "A"
        }
    }
}
```

**400 Bad Request - File Upload Error:**
```json
{
    "success": false,
    "message": "Lỗi upload CV: File size exceeds maximum limit of 5MB",
    "error": "FILE_UPLOAD_ERROR"
}
```

**429 Too Many Requests - Rate Limit:**
```json
{
    "success": false,
    "message": "Dữ liệu không hợp lệ",
    "error": "VALIDATION_ERROR",
    "errors": {
        "email": ["Bạn đã gửi quá nhiều đơn ứng tuyển. Vui lòng thử lại sau 1 giờ."]
    }
}
```

**500 Internal Server Error:**
```json
{
    "success": false,
    "message": "Đã xảy ra lỗi khi gửi hồ sơ. Vui lòng thử lại sau.",
    "error": "INTERNAL_ERROR"
}
```

---

### 2. Get User Applications

Lấy danh sách tất cả đơn ứng tuyển của user hiện tại.

**Endpoint:** `GET /job-applications`

**Authentication:** Required (Sanctum token)

**Headers:**
```http
Accept: application/json
Authorization: Bearer {token}
```

**Query Parameters:**

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `page` | integer | No | 1 | Số trang |
| `per_page` | integer | No | 10 | Số items per page |

**Request Example:**

```bash
curl -X GET "https://lamgame.localhost/api/job-applications?page=1&per_page=10" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer your-token"
```

**Success Response (200 OK):**

```json
{
    "success": true,
    "message": "Lấy danh sách ứng tuyển thành công",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 123,
                "job_id": 36,
                "applicant_user_id": 45,
                "applicant_name": "Nguyễn Văn A",
                "applicant_email": "user@example.com",
                "applicant_phone": "0936666809",
                "status": "pending",
                "applied_at": "2025-12-09T17:30:00.000000Z",
                "application_code": "JA-20251209-36-A1B2",
                "created_at": "2025-12-09T17:30:00.000000Z",
                "job": {
                    "id": 36,
                    "sku": "job-senior-game-dev",
                    "name": "Senior Game Developer"
                }
            },
            {
                "id": 122,
                "job_id": 35,
                "applicant_user_id": 45,
                "applicant_name": "Nguyễn Văn A",
                "applicant_email": "user@example.com",
                "applicant_phone": "0936666809",
                "status": "reviewed",
                "applied_at": "2025-12-08T10:00:00.000000Z",
                "application_code": "JA-20251208-35-B2C3",
                "created_at": "2025-12-08T10:00:00.000000Z",
                "job": {
                    "id": 35,
                    "sku": "job-unity-developer",
                    "name": "Unity Developer"
                }
            }
        ],
        "first_page_url": "https://lamgame.localhost/api/job-applications?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "https://lamgame.localhost/api/job-applications?page=1",
        "links": [...],
        "next_page_url": null,
        "path": "https://lamgame.localhost/api/job-applications",
        "per_page": 10,
        "prev_page_url": null,
        "to": 2,
        "total": 2
    }
}
```

**Error Response (401 Unauthorized):**

```json
{
    "success": false,
    "message": "Unauthorized",
    "error": "AUTHENTICATION_REQUIRED"
}
```

---

### 3. Get Application Status

Xem trạng thái của một đơn ứng tuyển cụ thể.

**Endpoint:** `GET /job-applications/{applicationId}/status`

**Authentication:** Optional

**Headers:**
```http
Accept: application/json
Authorization: Bearer {token} (optional)
```

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `applicationId` | integer | Yes | ID của đơn ứng tuyển |

**Query Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `email` | string | Yes (for guests) | Email để xác thực (chỉ cho guest users) |

**Request Example (Authenticated User):**

```bash
curl -X GET "https://lamgame.localhost/api/job-applications/123/status" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer your-token"
```

**Request Example (Guest User):**

```bash
curl -X GET "https://lamgame.localhost/api/job-applications/123/status?email=user@example.com" \
  -H "Accept: application/json"
```

**Success Response (200 OK):**

```json
{
    "success": true,
    "message": "Lấy trạng thái ứng tuyển thành công",
    "data": {
        "id": 123,
        "status": "reviewed",
        "applied_at": "09/12/2025 17:30",
        "job_title": "Senior Game Developer",
        "employer_notes": null
    }
}
```

**Error Response (400 Bad Request - Missing Email):**

```json
{
    "success": false,
    "message": "Email required for guest users",
    "error": "EMAIL_REQUIRED"
}
```

**Error Response (404 Not Found):**

```json
{
    "success": false,
    "message": "Không tìm thấy đơn ứng tuyển",
    "error": "APPLICATION_NOT_FOUND"
}
```

---

## Status Values

| Status | Description | Vietnamese |
|--------|-------------|------------|
| `pending` | Waiting for review | Chờ xử lý |
| `reviewed` | Employer has viewed | Đã xem |
| `shortlisted` | Selected for next round | Lọt vòng |
| `rejected` | Not selected | Từ chối |
| `accepted` | Offer extended | Chấp nhận |
| `cancelled` | Cancelled by applicant | Đã hủy |

---

## Rate Limiting

### Limits

- **Per Email:** 3 applications per hour
- **Per IP:** 5 applications per hour

### Response when rate limited:

```json
{
    "success": false,
    "message": "Dữ liệu không hợp lệ",
    "error": "VALIDATION_ERROR",
    "errors": {
        "email": ["Bạn đã gửi quá nhiều đơn ứng tuyển. Vui lòng thử lại sau 1 giờ."]
    }
}
```

---

## File Upload Specifications

### Allowed File Types

- **PDF:** `.pdf` (application/pdf)
- **Microsoft Word:** `.doc` (application/msword)
- **Microsoft Word (OpenXML):** `.docx` (application/vnd.openxmlformats-officedocument.wordprocessingml.document)

### File Size Limit

**Maximum:** 5MB (5,120 KB)

### File Validation

1. **MIME Type Check:** Validates actual file type
2. **Extension Check:** Validates file extension
3. **File Signature Check:** Verifies file magic bytes
4. **Size Check:** Ensures file is not empty and under limit
5. **Readability Check:** Ensures file can be read

### File Storage

- **Disk:** Private (not publicly accessible)
- **Path:** `storage/app/private/cvs/{YYYY}/{MM}/{filename}`
- **Filename Format:** `{sanitized_name}_{YYYYMMDD_HHmmss}_{uniqid}.{ext}`

**Example:**
```
cvs/2025/12/nguyen_van_a_cv_20251209_173045_abc123def456.pdf
```

---

## Error Codes Reference

| Code | HTTP Status | Description |
|------|-------------|-------------|
| `JOB_NOT_FOUND` | 404 | Job ID không tồn tại |
| `DUPLICATE_APPLICATION` | 409 | User đã ứng tuyển job này rồi |
| `VALIDATION_ERROR` | 422 | Dữ liệu input không hợp lệ |
| `FILE_UPLOAD_ERROR` | 400 | Lỗi khi upload file CV |
| `AUTHENTICATION_REQUIRED` | 401 | Endpoint yêu cầu đăng nhập |
| `EMAIL_REQUIRED` | 400 | Guest user cần cung cấp email |
| `APPLICATION_NOT_FOUND` | 404 | Application ID không tồn tại |
| `INTERNAL_ERROR` | 500 | Lỗi server nội bộ |

---

## Testing Examples

### Postman Collection

```json
{
    "info": {
        "name": "Job Application API",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "item": [
        {
            "name": "Submit Application",
            "request": {
                "method": "POST",
                "header": [
                    {
                        "key": "Accept",
                        "value": "application/json"
                    },
                    {
                        "key": "X-CSRF-TOKEN",
                        "value": "{{csrf_token}}"
                    }
                ],
                "body": {
                    "mode": "formdata",
                    "formdata": [
                        {
                            "key": "full_name",
                            "value": "Nguyễn Văn A",
                            "type": "text"
                        },
                        {
                            "key": "email",
                            "value": "user@example.com",
                            "type": "text"
                        },
                        {
                            "key": "phone",
                            "value": "0936666809",
                            "type": "text"
                        },
                        {
                            "key": "cv",
                            "type": "file",
                            "src": "/path/to/cv.pdf"
                        },
                        {
                            "key": "cover_letter",
                            "value": "Tôi rất quan tâm...",
                            "type": "text"
                        }
                    ]
                },
                "url": {
                    "raw": "{{base_url}}/api/jobs/36/apply",
                    "host": ["{{base_url}}"],
                    "path": ["api", "jobs", "36", "apply"]
                }
            }
        }
    ]
}
```

### PHP Example

```php
use Illuminate\Support\Facades\Http;

$response = Http::attach(
    'cv', file_get_contents('/path/to/cv.pdf'), 'cv.pdf'
)->post('https://lamgame.localhost/api/jobs/36/apply', [
    'full_name' => 'Nguyễn Văn A',
    'email' => 'user@example.com',
    'phone' => '0936666809',
    'cover_letter' => 'Tôi rất quan tâm...',
    'experience' => 'junior'
]);

$data = $response->json();
```

---

## Webhooks (Future)

**Coming soon:** Webhook notifications for application status changes.

---

## Changelog

### v1.0.0 (2025-12-09)
- Initial API release
- Submit application endpoint
- Get user applications endpoint
- Get application status endpoint
- File upload with validation
- Rate limiting
- Email notifications
