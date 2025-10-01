# API Dashboard Integration Guide

## Tổng quan

API Dashboard cung cấp các endpoint để quản lý job postings và applications cho employers. API này cho phép:

- Xem tổng quan dashboard với jobs và applications mới nhất
- Quản lý applications cho từng job posting
- Cập nhật trạng thái applications (pending, reviewed, shortlisted, accepted, rejected)

## Authentication

Tất cả endpoints yêu cầu authentication với Bearer token sử dụng Laravel Sanctum.

### Lấy Authentication Token

```bash
POST /api/auth/login
Content-Type: application/json

{
    "email": "employer@example.com",
    "password": "password"
}
```

Response:
```json
{
    "success": true,
    "token": "14|oGwpGs68jwD4vqoZThEyXwm4Y156qdQkQYvNgCHDd63fea1a",
    "user": {
        "id": 1,
        "email": "employer@example.com",
        "first_name": "John",
        "last_name": "Doe"
    }
}
```

## Base URL

```
https://lamgame.localhost/api/dashboard/
```

## Rate Limiting

- 60 requests per minute per authenticated user
- Headers trả về: `X-RateLimit-Limit`, `X-RateLimit-Remaining`

---

## Endpoints

### 1. Dashboard Overview

Lấy thông tin tổng quan dashboard với 5 jobs mới nhất và 5 applications mới nhất.

**Endpoint:** `GET /api/dashboard/`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
    "success": true,
    "message": "Dashboard data retrieved successfully",
    "data": {
        "recent_jobs": [
            {
                "id": 1,
                "title": "Senior PHP Developer",
                "company_info": {
                    "name": "Tech Company",
                    "position": "Senior PHP Developer"
                },
                "created_at": "2025-10-01T15:30:00.000000Z",
                "is_urgent": false,
                "is_featured": true
            }
        ],
        "recent_applications": [
            {
                "id": 1,
                "job_id": 1,
                "job_title": "Senior PHP Developer",
                "applicant_name": "John Doe",
                "applicant_email": "john@example.com",
                "status": "pending",
                "applied_at": "2025-10-01 15:48:14",
                "applied_at_human": "6 phút trước"
            }
        ],
        "statistics": {
            "total_jobs": 5,
            "total_applications": 12,
            "pending_applications": 8,
            "jobs_with_applications": 3
        }
    }
}
```

### 2. Job Applications Detail

Lấy danh sách applications cho một job cụ thể.

**Endpoint:** `GET /api/dashboard/jobs/{jobId}/applications`

**Parameters:**
- `jobId` (integer): ID của job posting

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response:**
```json
{
    "success": true,
    "message": "Job applications retrieved successfully",
    "data": {
        "job": {
            "id": 1,
            "title": "Senior PHP Developer",
            "description": "Job description...",
            "company_info": {
                "name": "Tech Company",
                "contact": {
                    "email": "hr@techcompany.com",
                    "phone": "0901234567"
                }
            }
        },
        "applications": [
            {
                "id": 1,
                "applicant_name": "John Doe",
                "applicant_email": "john@example.com",
                "applicant_phone": "0909123456",
                "cover_letter": "I am very interested in this position...",
                "resume_file_path": "/storage/resumes/john_doe_resume.pdf",
                "status": "pending",
                "employer_notes": null,
                "applied_at": "2025-10-01 15:48:14",
                "applied_at_human": "7 phút trước",
                "additional_info": {
                    "years_experience": 5,
                    "preferred_salary": "2000-3000 USD"
                }
            }
        ],
        "statistics": {
            "total_applications": 8,
            "pending": 5,
            "reviewed": 2,
            "shortlisted": 1,
            "accepted": 0,
            "rejected": 0
        }
    }
}
```

### 3. Update Application Status

Cập nhật trạng thái của một application.

**Endpoint:** `PUT /api/dashboard/applications/{applicationId}/status`

**Parameters:**
- `applicationId` (integer): ID của application

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "status": "reviewed",
    "employer_notes": "Application looks promising. Will schedule interview."
}
```

**Validation Rules:**
- `status`: required, must be one of: `pending`, `reviewed`, `shortlisted`, `rejected`, `accepted`
- `employer_notes`: optional, string, max 1000 characters

**Response:**
```json
{
    "success": true,
    "message": "Application status updated successfully",
    "data": {
        "id": 1,
        "status": "reviewed",
        "employer_notes": "Application looks promising. Will schedule interview.",
        "updated_at": "2025-10-01 16:20:30"
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
    "error": "Detailed error description (only in debug mode)"
}
```

### Common HTTP Status Codes

- `200 OK`: Request successful
- `400 Bad Request`: Invalid request data
- `401 Unauthorized`: Missing or invalid token
- `403 Forbidden`: Access denied
- `404 Not Found`: Resource not found
- `422 Unprocessable Entity`: Validation errors
- `429 Too Many Requests`: Rate limit exceeded
- `500 Internal Server Error`: Server error

### Validation Error Example

```json
{
    "success": false,
    "message": "The given data was invalid.",
    "errors": {
        "status": [
            "The selected status is invalid."
        ]
    }
}
```

---

## Application Status Flow

```
pending → reviewed → shortlisted → accepted
   ↓         ↓           ↓
rejected  rejected    rejected
```

**Status Descriptions:**
- `pending`: Mới submit, chưa được xem
- `reviewed`: Đã được xem và đánh giá
- `shortlisted`: Được chọn vào danh sách ngắn
- `accepted`: Được chấp nhận
- `rejected`: Bị từ chối (có thể từ bất kỳ status nào)

---

## Integration Examples

### JavaScript/jQuery Example

```javascript
// Dashboard overview
async function loadDashboard() {
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch('/api/dashboard/', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayDashboardData(data.data);
        } else {
            console.error('Error:', data.message);
        }
    } catch (error) {
        console.error('Request failed:', error);
    }
}

// Update application status
async function updateApplicationStatus(applicationId, status, notes = '') {
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch(`/api/dashboard/applications/${applicationId}/status`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: status,
                employer_notes: notes
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            console.log('Status updated:', data.data);
            // Refresh applications list
            loadJobApplications();
        } else {
            console.error('Error:', data.message);
        }
    } catch (error) {
        console.error('Request failed:', error);
    }
}
```

### PHP/Laravel Example

```php
<?php

class DashboardService
{
    protected $baseUrl;
    protected $token;
    
    public function __construct($baseUrl, $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }
    
    public function getDashboardData()
    {
        $response = Http::withToken($this->token)
            ->accept('application/json')
            ->get($this->baseUrl . '/dashboard/');
            
        return $response->json();
    }
    
    public function getJobApplications($jobId)
    {
        $response = Http::withToken($this->token)
            ->accept('application/json')
            ->get($this->baseUrl . "/dashboard/jobs/{$jobId}/applications");
            
        return $response->json();
    }
    
    public function updateApplicationStatus($applicationId, $status, $notes = null)
    {
        $response = Http::withToken($this->token)
            ->accept('application/json')
            ->put($this->baseUrl . "/dashboard/applications/{$applicationId}/status", [
                'status' => $status,
                'employer_notes' => $notes
            ]);
            
        return $response->json();
    }
}

// Usage
$dashboard = new DashboardService('https://lamgame.localhost/api', $userToken);
$dashboardData = $dashboard->getDashboardData();
```

### cURL Examples

```bash
# Get dashboard data
curl -X GET "https://lamgame.localhost/api/dashboard/" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Get job applications
curl -X GET "https://lamgame.localhost/api/dashboard/jobs/1/applications" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Update application status
curl -X PUT "https://lamgame.localhost/api/dashboard/applications/1/status" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "status": "reviewed",
    "employer_notes": "Good candidate, schedule interview"
  }'
```

---

## Security Considerations

1. **Token Security**: Store tokens securely, never expose in client-side code
2. **HTTPS Only**: Always use HTTPS in production
3. **Rate Limiting**: Implement proper rate limiting on client side
4. **Input Validation**: Validate all user inputs before sending to API
5. **Error Handling**: Don't expose sensitive information in error messages

---

## Development Notes

### Database Schema

**job_applications table:**
```sql
CREATE TABLE job_applications (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    job_id INT UNSIGNED NOT NULL,
    applicant_user_id INT UNSIGNED NOT NULL,
    applicant_name VARCHAR(255) NOT NULL,
    applicant_email VARCHAR(255) NOT NULL,
    applicant_phone VARCHAR(255) NULL,
    cover_letter TEXT NULL,
    resume_file_path VARCHAR(255) NULL,
    additional_info JSON NULL,
    status ENUM('pending', 'reviewed', 'shortlisted', 'rejected', 'accepted') DEFAULT 'pending',
    employer_notes TEXT NULL,
    applied_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (job_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (applicant_user_id) REFERENCES customers(id) ON DELETE CASCADE,
    
    UNIQUE KEY unique_application (job_id, applicant_user_id),
    INDEX idx_job_status (job_id, status),
    INDEX idx_applicant_status (applicant_user_id, status),
    INDEX idx_applied_at (applied_at)
);
```

### Model Relationships

```php
// JobApplication Model
public function job(): BelongsTo
{
    return $this->belongsTo(Product::class, 'job_id');
}

public function applicant(): BelongsTo  
{
    return $this->belongsTo(Customer::class, 'applicant_user_id');
}
```

---

## Troubleshooting

### Common Issues

1. **401 Unauthorized**
   - Check if token is valid and not expired
   - Ensure Bearer prefix in Authorization header

2. **404 Not Found**
   - Verify job/application ID exists
   - Check if job belongs to authenticated user

3. **422 Validation Error**
   - Check required fields and data types
   - Validate status enum values

4. **429 Rate Limit**
   - Implement exponential backoff
   - Cache responses when possible

### Debugging

Enable debug mode in `.env` for detailed error messages:
```
APP_DEBUG=true
```

Check Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

---

## Changelog

### Version 1.0.0 (2025-10-01)
- Initial release
- Dashboard overview endpoint
- Job applications management
- Application status updates
- Authentication with Sanctum
- Rate limiting implementation