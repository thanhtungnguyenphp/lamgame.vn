# Tài liệu Hệ thống Ứng tuyển Việc làm (Job Application System)

## Tổng quan

Hệ thống ứng tuyển việc làm cho phép người dùng (cả đã đăng nhập và khách) nộp hồ sơ ứng tuyển vào các vị trí tuyển dụng trên LAMGAME. Hệ thống bao gồm validation, upload CV, gửi email thông báo và quản lý trạng thái đơn ứng tuyển.

---

## Kiến trúc Hệ thống

```
┌─────────────────────────────────────────────────────────────┐
│                      FRONTEND LAYER                         │
│  - Form ứng tuyển (Vue.js/Blade)                           │
│  - Validation client-side                                   │
│  - File upload UI                                           │
└────────────────────┬────────────────────────────────────────┘
                     │ POST /api/jobs/{id}/apply
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                    CONTROLLER LAYER                         │
│  JobApplicationController                                   │
│  - apply()                                                  │
│  - getUserApplications()                                    │
│  - getApplicationStatus()                                   │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                   VALIDATION LAYER                          │
│  JobApplicationRequest                                      │
│  - Validate form data                                       │
│  - Rate limiting                                            │
│  - File validation                                          │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                    SERVICE LAYER                            │
│  ┌──────────────────────┐  ┌──────────────────────────┐   │
│  │ JobApplicationService│  │  FileUploadService       │   │
│  │ - createApplication  │  │  - uploadCV              │   │
│  │ - checkExisting      │  │  - validateFile          │   │
│  │ - sendNotifications  │  │  - deleteCV              │   │
│  │ - updateStatus       │  │  - getCVInfo             │   │
│  └──────────────────────┘  └──────────────────────────┘   │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                      MODEL LAYER                            │
│  JobApplication Model                                       │
│  - Relationships (job, applicant)                          │
│  - Scopes (status, recent)                                 │
│  - Attributes                                               │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│                    DATABASE LAYER                           │
│  job_applications table                                     │
│  - Application data                                         │
│  - Status tracking                                          │
│  - File paths                                               │
└─────────────────────────────────────────────────────────────┘
```

---

## Luồng xử lý (Flow)

### 1. Ứng tuyển mới

```
User điền form ứng tuyển
    ↓
Upload CV (PDF/DOC/DOCX)
    ↓
Submit form → POST /api/jobs/{id}/apply
    ↓
Validate request (JobApplicationRequest)
    ├─ Validate form fields
    ├─ Validate file (type, size, content)
    ├─ Check rate limiting
    └─ Clean input data
    ↓
Check job exists (Product model)
    ↓
Check duplicate application
    ├─ By user_id (if authenticated)
    └─ By email (if guest)
    ↓
Upload CV file (FileUploadService)
    ├─ Validate file security
    ├─ Generate unique filename
    ├─ Store in private storage
    └─ Return file path
    ↓
Create application (JobApplicationService)
    ├─ Generate application code
    ├─ Save to database
    └─ Log creation
    ↓
Send notifications
    ├─ Email to applicant (confirmation)
    └─ Email to employer (new application)
    ↓
Return success response
```

### 2. Xem trạng thái đơn ứng tuyển

```
User request → GET /api/job-applications/{id}/status
    ↓
Authenticate user (optional)
    ├─ If authenticated: check by user_id
    └─ If guest: require email parameter
    ↓
Find application
    ↓
Return status + details
```

### 3. Xem danh sách đơn đã nộp

```
User request → GET /api/job-applications
    ↓
Authenticate user (required)
    ↓
Get user's applications
    ├─ Filter by status
    ├─ Paginate results
    └─ Include job details
    ↓
Return list
```

---

## Database Schema

### Bảng: `job_applications`

```sql
CREATE TABLE job_applications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id INT UNSIGNED NOT NULL,
    applicant_user_id INT UNSIGNED NULL,
    applicant_name VARCHAR(255) NOT NULL,
    applicant_email VARCHAR(255) NOT NULL,
    applicant_phone VARCHAR(255) NULL,
    cover_letter TEXT NULL,
    resume_file_path VARCHAR(255) NULL,
    additional_info JSON NULL,
    status ENUM('pending', 'reviewed', 'shortlisted', 'rejected', 'accepted') DEFAULT 'pending',
    employer_notes TEXT NULL,
    applied_at TIMESTAMP NOT NULL,
    application_code VARCHAR(50) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (job_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (applicant_user_id) REFERENCES customers(id) ON DELETE CASCADE,
    
    INDEX idx_job_status (job_id, status),
    INDEX idx_applicant_status (applicant_user_id, status),
    INDEX idx_applied_at (applied_at),
    INDEX idx_application_code (application_code)
);
```

**Các trường quan trọng:**

- `job_id`: ID của job (từ bảng products)
- `applicant_user_id`: ID user (NULL nếu guest)
- `applicant_name`: Họ tên người ứng tuyển
- `applicant_email`: Email liên hệ
- `applicant_phone`: Số điện thoại
- `cover_letter`: Thư giới thiệu
- `resume_file_path`: Đường dẫn file CV
- `additional_info`: JSON chứa thông tin bổ sung (experience, IP, user agent)
- `status`: Trạng thái đơn ứng tuyển
- `employer_notes`: Ghi chú của nhà tuyển dụng
- `applied_at`: Thời gian nộp đơn
- `application_code`: Mã đơn ứng tuyển (format: JA-YYYYMMDD-{JobID}-{Random4})

---

## API Endpoints

### 1. POST /api/jobs/{jobId}/apply

Nộp đơn ứng tuyển.

**Request:**
```http
POST /api/jobs/36/apply
Content-Type: multipart/form-data
X-CSRF-TOKEN: {token}

full_name: Nguyễn Văn A
email: user@example.com
phone: 0936666809
cv: [file]
cover_letter: Tôi rất quan tâm...
experience: junior (optional)
```

**Response Success (201):**
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

**Response Error - Duplicate (409):**
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

### 2. GET /api/job-applications

Lấy danh sách đơn ứng tuyển của user (yêu cầu đăng nhập).

**Response (200):**
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
                "applicant_name": "Nguyễn Văn A",
                "status": "pending",
                "applied_at": "2025-12-09T17:30:00",
                "job": {
                    "id": 36,
                    "name": "Senior Game Developer"
                }
            }
        ],
        "per_page": 10,
        "total": 5
    }
}
```

### 3. GET /api/job-applications/{id}/status

Xem trạng thái đơn ứng tuyển.

**Query Parameters:**
- `email` (required for guests): Email để xác thực

**Response (200):**
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

---

## Validation Rules

### Form Fields

| Field | Rules | Description |
|-------|-------|-------------|
| `full_name` | required, string, min:2, max:100, regex | Họ tên (chỉ chữ cái, khoảng trắng, dấu) |
| `email` | required, email:rfc,dns, max:255 | Email hợp lệ |
| `phone` | required, regex (VN format) | Số điện thoại Việt Nam |
| `cv` | required, file, mimes:pdf,doc,docx, max:5MB | File CV |
| `cover_letter` | nullable, string, max:2000 | Thư giới thiệu |
| `experience` | nullable, in:fresher,junior,middle,senior | Mức kinh nghiệm |

### File Validation

**Allowed formats:**
- PDF (.pdf)
- Microsoft Word (.doc, .docx)

**Max size:** 5MB

**Security checks:**
- MIME type validation
- File extension validation
- File signature verification (magic bytes)
- Empty file check
- Readable file check

### Rate Limiting

**By email:** Max 3 applications per hour  
**By IP:** Max 5 applications per hour

---

## File Upload

### Storage

**Disk:** `private` (không public access)  
**Path format:** `cvs/{YYYY}/{MM}/{filename}`  
**Filename format:** `{original}_{YYYYMMDD_HHmmss}_{uniqid}.{ext}`

**Example:**
```
cvs/2025/12/nguyen_van_a_cv_20251209_173045_abc123.pdf
```

### Security

1. **File validation:**
   - Check MIME type
   - Check extension
   - Verify file signature (magic bytes)
   - Check file size

2. **Filename sanitization:**
   - Remove dangerous characters
   - Limit length (50 chars)
   - Add timestamp + unique ID

3. **Storage:**
   - Private disk (không truy cập trực tiếp)
   - Temporary signed URLs (1 hour expiry)

4. **Cleanup:**
   - Auto cleanup files > 90 days (không referenced)

---

## Email Notifications

### 1. Email cho Applicant (Confirmation)

**Class:** `ApplicationReceivedMail`  
**Subject:** "Đã nhận được đơn ứng tuyển của bạn"

**Nội dung:**
- Xác nhận đã nhận đơn
- Mã đơn ứng tuyển
- Thông tin vị trí
- Thời gian xử lý dự kiến
- Link theo dõi trạng thái

### 2. Email cho Employer (New Application)

**Class:** `NewApplicationMail`  
**Subject:** "Đơn ứng tuyển mới cho {Job Title}"

**Nội dung:**
- Thông tin ứng viên
- Link xem CV
- Link quản lý đơn ứng tuyển
- Thông tin liên hệ

---

## Status Flow

```
pending (Chờ xử lý)
    ↓
reviewed (Đã xem)
    ↓
    ├─→ shortlisted (Lọt vòng)
    │       ↓
    │   accepted (Chấp nhận)
    │
    └─→ rejected (Từ chối)

cancelled (Hủy bỏ - do applicant)
```

**Status descriptions:**

- `pending`: Đơn mới, chưa xem
- `reviewed`: Nhà tuyển dụng đã xem
- `shortlisted`: Lọt vào danh sách ngắn
- `rejected`: Không phù hợp
- `accepted`: Chấp nhận ứng viên
- `cancelled`: Ứng viên hủy đơn

---

## Services

### JobApplicationService

**Methods:**

1. `checkExistingApplication($jobId, $email, $userId)` - Kiểm tra đã ứng tuyển chưa
2. `createApplication($data)` - Tạo đơn ứng tuyển mới
3. `generateApplicationCode($jobId)` - Tạo mã đơn unique
4. `sendNotifications($application, $job)` - Gửi email thông báo
5. `updateApplicationStatus($id, $status, $notes)` - Cập nhật trạng thái
6. `getJobApplications($jobId, $filters)` - Lấy danh sách đơn theo job
7. `getJobApplicationStats($jobId)` - Thống kê đơn ứng tuyển
8. `bulkUpdateApplications($ids, $status, $notes)` - Cập nhật hàng loạt

### FileUploadService

**Methods:**

1. `uploadCV($file)` - Upload file CV
2. `validateFile($file)` - Validate file
3. `verifyFileContents($file)` - Verify file signature
4. `generateUniqueFilename($file)` - Tạo tên file unique
5. `sanitizeFilename($filename)` - Làm sạch tên file
6. `deleteCV($filePath)` - Xóa file CV
7. `getCVDownloadUrl($filePath)` - Lấy URL download (signed)
8. `getCVInfo($filePath)` - Lấy thông tin file
9. `cleanupOldCVs($daysOld)` - Dọn dẹp file cũ

---

## Error Handling

### Common Errors

| Error Code | HTTP Status | Description |
|------------|-------------|-------------|
| `JOB_NOT_FOUND` | 404 | Không tìm thấy job |
| `DUPLICATE_APPLICATION` | 409 | Đã ứng tuyển rồi |
| `VALIDATION_ERROR` | 422 | Dữ liệu không hợp lệ |
| `FILE_UPLOAD_ERROR` | 400 | Lỗi upload file |
| `AUTHENTICATION_REQUIRED` | 401 | Cần đăng nhập |
| `APPLICATION_NOT_FOUND` | 404 | Không tìm thấy đơn |
| `INTERNAL_ERROR` | 500 | Lỗi server |

---

## Testing

### Manual Testing Checklist

**Ứng tuyển:**
- [ ] Form hiển thị đầy đủ fields
- [ ] Validation hoạt động
- [ ] Upload CV thành công (PDF, DOC, DOCX)
- [ ] Upload file > 5MB bị reject
- [ ] Upload file sai format bị reject
- [ ] Duplicate application bị chặn
- [ ] Email confirmation được gửi
- [ ] Email employer được gửi
- [ ] File CV được lưu đúng path
- [ ] Application code được tạo

**Xem trạng thái:**
- [ ] User đã login xem được đơn của mình
- [ ] Guest cần email để xem
- [ ] Không xem được đơn của người khác

**Rate limiting:**
- [ ] Max 3 đơn/hour per email
- [ ] Max 5 đơn/hour per IP

---

## Security Considerations

### 1. File Upload Security
- ✅ MIME type validation
- ✅ File extension validation
- ✅ File signature verification
- ✅ Size limit (5MB)
- ✅ Private storage
- ✅ Filename sanitization
- ✅ Temporary signed URLs

### 2. Data Security
- ✅ CSRF protection
- ✅ Input sanitization
- ✅ SQL injection protection (Eloquent)
- ✅ XSS protection (Blade escaping)

### 3. Privacy
- ✅ CV files in private storage
- ✅ Guest users: email verification required
- ✅ Logged users: user_id verification

### 4. Rate Limiting
- ✅ Per email limit
- ✅ Per IP limit
- ✅ Prevent spam applications

---

## Performance Optimization

### Database Indexes

```sql
INDEX idx_job_status (job_id, status)
INDEX idx_applicant_status (applicant_user_id, status)
INDEX idx_applied_at (applied_at)
INDEX idx_application_code (application_code)
```

### Caching

- Cache job details khi apply
- Cache application stats

### Queue

- Email notifications (queued)
- File cleanup (scheduled)

---

## Monitoring & Logging

### Logs

**Application created:**
```php
Log::info('New job application created', [
    'application_id' => $id,
    'job_id' => $jobId,
    'applicant_email' => $email,
]);
```

**File uploaded:**
```php
Log::info('CV file uploaded successfully', [
    'original_name' => $name,
    'stored_path' => $path,
    'file_size' => $size,
]);
```

**Email sent:**
```php
Log::info('Applicant notification sent successfully', [
    'application_id' => $id,
    'email' => $email,
]);
```

### Metrics to Monitor

- Applications per day
- Success rate
- File upload failures
- Email delivery rate
- Average response time
- Duplicate attempts

---

## Related Files

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── JobApplicationController.php
│   └── Requests/
│       └── Api/
│           └── JobApplicationRequest.php
├── Models/
│   └── JobApplication.php
├── Services/
│   ├── JobApplicationService.php
│   └── FileUploadService.php
├── Mail/
│   ├── ApplicationReceivedMail.php
│   └── NewApplicationMail.php
└── Exceptions/
    └── FileUploadException.php

database/
└── migrations/
    └── 2025_10_01_151839_create_job_applications_table.php

routes/
└── api.php (job application routes)

storage/
└── app/
    └── private/
        └── cvs/
            └── {YYYY}/
                └── {MM}/
                    └── {files}
```

---

## Future Improvements

### Short-term
1. Add video introduction upload
2. LinkedIn profile integration
3. Application tracking dashboard
4. Email templates customization
5. SMS notifications

### Medium-term
1. AI-powered CV parsing
2. Skill matching algorithm
3. Interview scheduling
4. Applicant ranking system
5. Bulk actions for employers

### Long-term
1. Video interview integration
2. Assessment tests
3. Background check integration
4. Offer letter generation
5. Onboarding workflow

---

## Changelog

### Version 1.0.0 (2025-12-09)
- Initial job application system
- File upload with security validation
- Email notifications
- Rate limiting
- Status tracking
- Guest user support
