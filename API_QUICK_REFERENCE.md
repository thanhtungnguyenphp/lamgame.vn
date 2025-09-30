# 🚀 Job Posting API - Quick Reference

## Endpoints Overview

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/jobs` | List jobs with filters | ❌ |
| POST | `/api/jobs` | Create new job | ❌* |
| GET | `/api/jobs/{id}` | Get job details | ❌ |
| PUT | `/api/jobs/{id}` | Update job | ❌* |
| DELETE | `/api/jobs/{id}` | Delete job | ❌* |
| GET | `/api/jobs/categories` | Get job categories | ❌ |
| GET | `/api/jobs/attributes` | Get job attributes | ❌ |
| POST | `/api/jobs/bulk` | Bulk create jobs | ❌* |
| POST | `/api/jobs/{id}/publish` | Publish job | ❌* |
| POST | `/api/jobs/{id}/unpublish` | Unpublish job | ❌* |

*\* Currently no auth required for testing. Enable in production.*

## Base URL
```
http://localhost:8000/api/jobs
```

## Quick Examples

### 1. List Jobs
```bash
curl -X GET "http://localhost:8000/api/jobs?search=Unity&per_page=5" \
  -H "Accept: application/json"
```

### 2. Create Job
```bash
curl -X POST "http://localhost:8000/api/jobs" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Unity Developer - Test Company",
    "company_name": "Test Company",
    "description": "<p>Job description</p>",
    "short_description": "Job summary",
    "job_type": "full-time",
    "experience_level": "junior",
    "salary_range": "20m-30m",
    "job_location": "Hồ Chí Minh",
    "company_size": "Nhỏ (10-50 người)",
    "required_skills": ["Unity", "C#"],
    "contact_email": "hr@test.com",
    "application_method": "email",
    "categories": [102]
  }'
```

### 3. Get Job Details
```bash
curl -X GET "http://localhost:8000/api/jobs/15" \
  -H "Accept: application/json"
```

### 4. Update Job
```bash
curl -X PUT "http://localhost:8000/api/jobs/15" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "salary_range": "30m-50m",
    "is_urgent": true
  }'
```

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": { ... },
  "pagination": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error message",
  "errors": { ... }
}
```

## Common Filters

| Filter | Type | Examples |
|--------|------|----------|
| `search` | string | `Unity`, `Developer` |
| `job_type` | string | `full-time`, `remote` |
| `location` | string | `Hồ Chí Minh`, `Hà Nội` |
| `company` | string | `VNG`, `Gameloft` |
| `is_urgent` | boolean | `true`, `false` |
| `is_featured` | boolean | `true`, `false` |
| `per_page` | integer | `10`, `25`, `50` (max) |

## Required Fields for Creation

```javascript
const requiredFields = {
  title: "string, max 255",
  company_name: "string, max 255", 
  description: "string",
  short_description: "string, max 500",
  job_type: "enum: full-time|part-time|contract|freelance|internship|remote|hybrid",
  experience_level: "enum: fresher|junior|middle|senior|lead|director",
  salary_range: "string",
  job_location: "string",
  company_size: "string",
  required_skills: "array, min 1",
  contact_email: "email",
  application_method: "enum: email|online|direct|website",
  categories: "array, min 1"
};
```

## Job Object Structure

```javascript
{
  id: 1,
  sku: "JOB_COMPANY_TITLE_2025",
  title: "Job Title - Company Name",
  slug: "job-title-company-name",
  short_description: "Brief summary",
  description: "<html>Full description</html>",
  
  // Job Details
  job_type: "Full-time",
  experience_level: "Senior (5+ năm)",
  salary_range: "50-80 triệu",
  job_location: "Hồ Chí Minh",
  company_size: "Tập đoàn (1000+ người)",
  
  // Requirements
  required_skills: ["Unity", "C#", "Git"],
  education_level: "Đại học",
  english_level: "Thành thạo",
  
  // Benefits & Contact
  job_benefits: ["Bảo hiểm sức khỏe", "Thưởng hiệu suất"],
  application_deadline: {
    raw: "2025-12-31",
    formatted: "31/12/2025",
    iso: "2025-12-31T00:00:00Z",
    human: "trong 2 tháng"
  },
  contact_email: "careers@company.com",
  contact_phone: "028-1234-5678",
  company_website: "https://company.com",
  application_method: "Ứng tuyển online",
  
  // Status
  is_urgent: false,
  is_featured: true,
  status: true,
  
  // Meta
  categories: [{ id: 102, name: "Việc Làm", ... }],
  meta: {
    title: "SEO title",
    description: "SEO description",
    keywords: "seo, keywords"
  },
  
  // Timestamps
  created_at: "2025-09-30T05:00:00Z",
  updated_at: "2025-09-30T05:00:00Z",
  
  // Computed
  days_remaining: 92,
  is_expired: false,
  company_info: {
    name: "Company Name",
    position: "Job Position",
    contact: { ... }
  }
}
```

## HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 404 | Not Found |
| 422 | Validation Error |
| 429 | Rate Limited (60/min) |
| 500 | Server Error |

## JavaScript Integration Examples

### Fetch API
```javascript
// Get jobs
const jobs = await fetch('/api/jobs?search=Unity')
  .then(res => res.json());

// Create job
const newJob = await fetch('/api/jobs', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(jobData)
}).then(res => res.json());
```

### Axios
```javascript
// Get jobs with filters
const response = await axios.get('/api/jobs', {
  params: {
    search: 'Unity',
    job_type: 'full-time',
    per_page: 10
  }
});

// Create job
const newJob = await axios.post('/api/jobs', jobData);
```

## Testing Commands

```bash
# Test server connectivity
curl -I http://localhost:8000

# Run comprehensive tests
php test_api.php

# Test specific endpoints
curl -X GET "http://localhost:8000/api/jobs/categories" -H "Accept: application/json"
curl -X GET "http://localhost:8000/api/jobs/attributes" -H "Accept: application/json"
```

## Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| 500 Server Error | Check database connection |
| Route not found | Run `php artisan route:clear` |
| Categories not found | Run seeder: `php artisan db:seed --class=JobPostingSeeder` |
| Validation failed | Check required fields and data format |
| Server not responding | Start server: `php artisan serve --port=8000` |

---

📚 **Full Documentation:** See `API_INTEGRATION_GUIDE.md` for complete details.

🧪 **Test File:** Run `test_api.php` for automated testing.

📧 **Support:** salegamevui@gmail.com