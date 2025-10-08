# Job Management System - API Documentation

## Base Information
- **Base URL**: `http://localhost:8000/api` (Development)
- **Authentication**: Bearer Token (Laravel Sanctum)
- **Content-Type**: `application/json`
- **Accept**: `application/json`

## Table of Contents

1. [Authentication](#authentication)
2. [Public Job APIs](#public-job-apis)
3. [User Job Management APIs](#user-job-management-apis)
4. [Job Analytics APIs](#job-analytics-apis)
5. [Bulk Operations APIs](#bulk-operations-apis)
6. [Import/Export APIs](#importexport-apis)
7. [Dashboard APIs](#dashboard-apis)
8. [Job Application APIs](#job-application-apis)
9. [Error Responses](#error-responses)

---

## Authentication

### Get User Info
**GET** `/user`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "email_verified_at": "2025-01-01T00:00:00.000000Z",
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-01T00:00:00.000000Z"
}
```

---

## Public Job APIs

### 1. Get All Jobs (Public)
**GET** `/jobs`

**Query Parameters:**
- `page` (integer, optional): Page number (default: 1)
- `per_page` (integer, optional): Items per page (max: 50, default: 15)
- `search` (string, optional): Search in title and description
- `location` (string, optional): Filter by location
- `job_type` (string, optional): Filter by employment type
- `salary_min` (integer, optional): Minimum salary filter
- `salary_max` (integer, optional): Maximum salary filter
- `is_urgent` (boolean, optional): Filter urgent jobs
- `is_featured` (boolean, optional): Filter featured jobs
- `order_by` (string, optional): Sort field (default: created_at)
- `order_direction` (string, optional): Sort direction (asc/desc, default: desc)

**Example Request:**
```
GET /api/jobs?page=1&per_page=10&search=developer&location=Ho Chi Minh&job_type=full-time
```

**Response:**
```json
{
  "success": true,
  "message": "Jobs retrieved successfully",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Senior PHP Developer",
        "short_description": "Join our team as a senior PHP developer...",
        "location": "Ho Chi Minh City",
        "company_name": "TechCorp Ltd",
        "salary_min": 2000,
        "salary_max": 3000,
        "job_type": "full-time",
        "experience_level": "senior",
        "is_urgent": false,
        "is_featured": true,
        "application_deadline": "2025-02-01T00:00:00.000000Z",
        "created_at": "2025-01-01T00:00:00.000000Z",
        "updated_at": "2025-01-01T00:00:00.000000Z"
      }
    ],
    "first_page_url": "http://localhost:8000/api/jobs?page=1",
    "from": 1,
    "last_page": 5,
    "last_page_url": "http://localhost:8000/api/jobs?page=5",
    "next_page_url": "http://localhost:8000/api/jobs?page=2",
    "path": "http://localhost:8000/api/jobs",
    "per_page": 10,
    "prev_page_url": null,
    "to": 10,
    "total": 50
  }
}
```

### 2. Get Job Detail (Public)
**GET** `/jobs/{id}`

**Path Parameters:**
- `id` (integer, required): Job ID

**Response:**
```json
{
  "success": true,
  "message": "Job retrieved successfully",
  "data": {
    "id": 1,
    "title": "Senior PHP Developer",
    "description": "We are looking for an experienced PHP developer...",
    "short_description": "Join our team as a senior PHP developer...",
    "location": "Ho Chi Minh City",
    "company_name": "TechCorp Ltd",
    "salary_min": 2000,
    "salary_max": 3000,
    "job_type": "full-time",
    "experience_level": "senior",
    "skills_required": "PHP, Laravel, MySQL, JavaScript",
    "job_requirements": "3+ years PHP experience...",
    "job_benefits": "Health insurance, flexible hours...",
    "contact_email": "hr@techcorp.com",
    "is_urgent": false,
    "is_featured": true,
    "application_deadline": "2025-02-01T00:00:00.000000Z",
    "categories": [
      {
        "id": 102,
        "name": "Information Technology"
      }
    ],
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z"
  }
}
```

### 3. Get Job Categories (Public)
**GET** `/jobs/categories`

**Response:**
```json
{
  "success": true,
  "message": "Categories retrieved successfully",
  "data": [
    {
      "id": 102,
      "name": "Information Technology",
      "slug": "information-technology",
      "job_count": 25
    },
    {
      "id": 103,
      "name": "Marketing",
      "slug": "marketing", 
      "job_count": 15
    }
  ]
}
```

### 4. Get Job Attributes (Public)
**GET** `/jobs/attributes`

**Response:**
```json
{
  "success": true,
  "message": "Attributes retrieved successfully",
  "data": {
    "job_types": ["full-time", "part-time", "contract", "internship", "freelance"],
    "experience_levels": ["entry", "mid", "senior", "lead", "executive"],
    "locations": ["Ho Chi Minh City", "Ha Noi", "Da Nang", "Can Tho"],
    "salary_ranges": [
      {"min": 0, "max": 500, "label": "Under $500"},
      {"min": 500, "max": 1000, "label": "$500 - $1000"},
      {"min": 1000, "max": 2000, "label": "$1000 - $2000"},
      {"min": 2000, "max": 5000, "label": "$2000 - $5000"},
      {"min": 5000, "max": null, "label": "Above $5000"}
    ]
  }
}
```

---

## User Job Management APIs

### 1. Get User's Jobs
**GET** `/user/jobs`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (integer, optional): Page number (default: 1)
- `per_page` (integer, optional): Items per page (max: 50, default: 15)
- `search` (string, optional): Search in title and description
- `status` (string, optional): Filter by status (active/inactive)
- `location` (string, optional): Filter by location
- `job_type` (string, optional): Filter by employment type
- `is_urgent` (boolean, optional): Filter urgent jobs
- `is_featured` (boolean, optional): Filter featured jobs
- `date_from` (date, optional): Filter jobs created from date (Y-m-d)
- `date_to` (date, optional): Filter jobs created to date (Y-m-d)
- `deadline_from` (date, optional): Filter by application deadline from
- `deadline_to` (date, optional): Filter by application deadline to
- `order_by` (string, optional): Sort field (default: created_at)
- `order_direction` (string, optional): Sort direction (asc/desc, default: desc)

**Response:** Same structure as public jobs API but only shows user's own jobs.

### 2. Create New Job
**POST** `/user/jobs`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "title": "Senior PHP Developer",
  "description": "We are looking for an experienced PHP developer to join our growing team...",
  "short_description": "Join our team as a senior PHP developer with competitive salary and great benefits.",
  "company_name": "TechCorp Ltd",
  "job_location": "Ho Chi Minh City",
  "salary_min": 2000,
  "salary_max": 3000,
  "job_type": "full-time",
  "experience_level": "senior",
  "skills_required": "PHP, Laravel, MySQL, JavaScript, Git",
  "job_requirements": "3+ years of PHP development experience, Knowledge of Laravel framework, Experience with MySQL database",
  "job_benefits": "Health insurance, flexible working hours, annual bonus, professional development opportunities",
  "application_deadline": "2025-02-01",
  "contact_email": "hr@techcorp.com",
  "is_urgent": false,
  "is_featured": true,
  "categories": [102]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Job created successfully",
  "data": {
    "id": 1,
    "title": "Senior PHP Developer",
    "description": "We are looking for an experienced PHP developer...",
    "short_description": "Join our team as a senior PHP developer...",
    "company_name": "TechCorp Ltd",
    "job_location": "Ho Chi Minh City",
    "salary_min": 2000,
    "salary_max": 3000,
    "job_type": "full-time",
    "experience_level": "senior",
    "skills_required": "PHP, Laravel, MySQL, JavaScript, Git",
    "job_requirements": "3+ years of PHP development experience...",
    "job_benefits": "Health insurance, flexible working hours...",
    "application_deadline": "2025-02-01T00:00:00.000000Z",
    "contact_email": "hr@techcorp.com",
    "is_urgent": false,
    "is_featured": true,
    "status": 1,
    "categories": [
      {
        "id": 102,
        "name": "Information Technology"
      }
    ],
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z"
  }
}
```

### 3. Get Specific User Job
**GET** `/user/jobs/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:** Same as create job response.

### 4. Update User Job
**PUT** `/user/jobs/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:** Same as create job (all fields optional).

**Response:** Same structure as create job response.

### 5. Delete User Job
**DELETE** `/user/jobs/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Job deleted successfully"
}
```

### 6. Toggle Job Status
**PATCH** `/user/jobs/{id}/toggle-status`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Job status updated successfully",
  "data": {
    "id": 1,
    "status": 1,
    "status_text": "Active",
    "updated_at": "2025-01-01T00:00:00.000000Z"
  }
}
```

### 7. Get User Job Statistics
**GET** `/user/jobs/statistics`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `date_from` (date, optional): Statistics from date (Y-m-d)
- `date_to` (date, optional): Statistics to date (Y-m-d)

**Response:**
```json
{
  "success": true,
  "message": "Statistics retrieved successfully",
  "data": {
    "overview": {
      "total_jobs": 25,
      "active_jobs": 20,
      "inactive_jobs": 5,
      "featured_jobs": 8,
      "urgent_jobs": 3,
      "expired_jobs": 2
    },
    "applications": {
      "total_applications": 150,
      "pending_applications": 45,
      "accepted_applications": 12,
      "rejected_applications": 93
    },
    "performance": {
      "average_applications_per_job": 6.0,
      "top_performing_job": {
        "id": 5,
        "title": "Frontend Developer",
        "applications_count": 25
      },
      "conversion_rate": 8.0
    },
    "trends": {
      "jobs_this_month": 8,
      "jobs_last_month": 6,
      "growth_rate": 33.33
    },
    "expiring_soon": {
      "count": 3,
      "jobs": [
        {
          "id": 10,
          "title": "Backend Developer",
          "deadline": "2025-01-15T00:00:00.000000Z",
          "days_remaining": 7
        }
      ]
    }
  }
}
```

### 8. Duplicate Job
**POST** `/user/jobs/{id}/duplicate`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body (Optional):**
```json
{
  "modifications": {
    "title": "Senior PHP Developer - Remote",
    "job_location": "Remote",
    "salary_max": 3500
  }
}
```

**Response:**
```json
{
  "success": true,
  "message": "Job duplicated successfully",
  "data": {
    "original_job_id": 1,
    "duplicated_job": {
      "id": 26,
      "title": "Senior PHP Developer - Remote",
      "job_location": "Remote",
      "salary_max": 3500,
      "created_at": "2025-01-01T00:00:00.000000Z"
    }
  }
}
```

### 9. Get Filter Options
**GET** `/user/jobs/filter-options`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Filter options retrieved successfully",
  "data": {
    "job_types": ["full-time", "part-time", "contract", "internship", "freelance"],
    "experience_levels": ["entry", "mid", "senior", "lead", "executive"],
    "locations": ["Ho Chi Minh City", "Ha Noi", "Da Nang", "Can Tho", "Remote"],
    "categories": [
      {"id": 102, "name": "Information Technology"},
      {"id": 103, "name": "Marketing"}
    ],
    "salary_ranges": [
      {"min": 0, "max": 500, "label": "Under $500"},
      {"min": 500, "max": 1000, "label": "$500 - $1000"}
    ],
    "date_ranges": [
      {"value": "today", "label": "Today"},
      {"value": "this_week", "label": "This Week"},
      {"value": "this_month", "label": "This Month"},
      {"value": "last_30_days", "label": "Last 30 Days"}
    ]
  }
}
```

### 10. Save Filter Template
**POST** `/user/jobs/filter-templates`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "Tech Jobs Remote",
  "description": "Filter for remote technology jobs",
  "filters": {
    "location": "Remote",
    "categories": [102],
    "job_type": "full-time",
    "salary_min": 2000
  }
}
```

**Response:**
```json
{
  "success": true,
  "message": "Filter template saved successfully",
  "data": {
    "id": 1,
    "name": "Tech Jobs Remote",
    "description": "Filter for remote technology jobs",
    "filters": {
      "location": "Remote",
      "categories": [102],
      "job_type": "full-time",
      "salary_min": 2000
    },
    "created_at": "2025-01-01T00:00:00.000000Z"
  }
}
```

### 11. Get Filter Templates
**GET** `/user/jobs/filter-templates`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Filter templates retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Tech Jobs Remote",
      "description": "Filter for remote technology jobs",
      "filters": {
        "location": "Remote",
        "categories": [102],
        "job_type": "full-time",
        "salary_min": 2000
      },
      "created_at": "2025-01-01T00:00:00.000000Z",
      "used_count": 5,
      "last_used_at": "2025-01-05T00:00:00.000000Z"
    }
  ]
}
```

### 12. Extend Job Deadline
**POST** `/user/jobs/{id}/extend-deadline`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "new_deadline": "2025-03-01",
  "reason": "Need more time to find qualified candidates"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Job deadline extended successfully",
  "data": {
    "id": 1,
    "title": "Senior PHP Developer",
    "old_deadline": "2025-02-01T00:00:00.000000Z",
    "new_deadline": "2025-03-01T00:00:00.000000Z",
    "extension_reason": "Need more time to find qualified candidates",
    "extended_at": "2025-01-01T00:00:00.000000Z"
  }
}
```

### 13. Preview Job
**GET** `/user/jobs/{id}/preview`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Job preview generated successfully",
  "data": {
    "preview_url": "http://localhost:8000/jobs/1/preview",
    "preview_html": "<html>...</html>",
    "seo_preview": {
      "title": "Senior PHP Developer - TechCorp Ltd",
      "description": "Join our team as a senior PHP developer with competitive salary...",
      "keywords": "PHP, Laravel, MySQL, JavaScript, developer, Ho Chi Minh"
    },
    "social_media_preview": {
      "facebook": {
        "title": "Senior PHP Developer",
        "description": "Great opportunity at TechCorp Ltd",
        "image": "http://localhost:8000/images/job-1-preview.jpg"
      },
      "linkedin": {
        "title": "Senior PHP Developer - TechCorp Ltd",
        "description": "We are looking for an experienced PHP developer..."
      }
    }
  }
}
```

### 14. Boost Job (Featured/Urgent)
**POST** `/user/jobs/{id}/boost`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "boost_type": "featured", // "featured" or "urgent" or "both"
  "duration_days": 30,
  "reason": "High priority position"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Job boosted successfully",
  "data": {
    "id": 1,
    "title": "Senior PHP Developer",
    "boost_type": "featured",
    "boost_started_at": "2025-01-01T00:00:00.000000Z",
    "boost_expires_at": "2025-01-31T00:00:00.000000Z",
    "is_featured": true,
    "is_urgent": false,
    "boost_cost": 50,
    "remaining_boosts": 2
  }
}
```

### 15. Get Job Templates
**GET** `/user/jobs/templates`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Job templates retrieved successfully",
  "data": {
    "user_templates": [
      {
        "id": 1,
        "name": "PHP Developer Template",
        "description": "Standard template for PHP developer positions",
        "template_data": {
          "title": "PHP Developer",
          "job_type": "full-time",
          "experience_level": "mid",
          "skills_required": "PHP, Laravel, MySQL"
        },
        "used_count": 5,
        "created_at": "2025-01-01T00:00:00.000000Z"
      }
    ],
    "organization_templates": [
      {
        "id": 10,
        "name": "Standard Developer Role",
        "description": "Organization-wide template for developer roles",
        "is_public": true,
        "created_by": "HR Team"
      }
    ],
    "system_templates": [
      {
        "id": 100,
        "name": "IT Support Specialist",
        "category": "Information Technology",
        "description": "Pre-built template for IT support roles"
      }
    ]
  }
}
```

### 16. Create Job from Template
**POST** `/user/jobs/from-template/{templateId}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body (Optional modifications):**
```json
{
  "modifications": {
    "title": "Senior PHP Developer - Updated",
    "salary_max": 3500,
    "job_location": "Remote"
  },
  "save_as_draft": true
}
```

**Response:**
```json
{
  "success": true,
  "message": "Job created from template successfully",
  "data": {
    "template_id": 1,
    "template_name": "PHP Developer Template",
    "created_job": {
      "id": 27,
      "title": "Senior PHP Developer - Updated",
      "status": "draft",
      "created_from_template": true,
      "created_at": "2025-01-01T00:00:00.000000Z"
    }
  }
}
```

---

## Job Analytics APIs

### 1. Get Analytics Overview
**GET** `/analytics/jobs/overview`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `date_from` (date, optional): Analytics from date (Y-m-d)
- `date_to` (date, optional): Analytics to date (Y-m-d)
- `job_ids` (array, optional): Specific job IDs to analyze

**Response:**
```json
{
  "success": true,
  "message": "Analytics overview retrieved successfully",
  "data": {
    "summary": {
      "total_views": 5420,
      "total_applications": 234,
      "total_jobs": 25,
      "average_conversion_rate": 4.32,
      "top_performing_job_id": 5
    },
    "metrics": {
      "views": {
        "total": 5420,
        "this_month": 1240,
        "last_month": 980,
        "growth_rate": 26.53
      },
      "applications": {
        "total": 234,
        "this_month": 67,
        "last_month": 45,
        "growth_rate": 48.89
      },
      "conversion_rates": {
        "overall": 4.32,
        "this_month": 5.40,
        "last_month": 4.59,
        "improvement": 0.81
      }
    },
    "top_jobs": [
      {
        "id": 5,
        "title": "Frontend Developer",
        "views": 450,
        "applications": 28,
        "conversion_rate": 6.22
      },
      {
        "id": 12,
        "title": "Backend Developer", 
        "views": 380,
        "applications": 19,
        "conversion_rate": 5.00
      }
    ],
    "trends": {
      "daily_views": [
        {"date": "2025-01-01", "views": 120},
        {"date": "2025-01-02", "views": 145}
      ],
      "daily_applications": [
        {"date": "2025-01-01", "applications": 8},
        {"date": "2025-01-02", "applications": 12}
      ]
    }
  }
}
```

### 2. Get Individual Job Analytics
**GET** `/analytics/jobs/{id}/analytics`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `date_from` (date, optional): Analytics from date (Y-m-d)
- `date_to` (date, optional): Analytics to date (Y-m-d)

**Response:**
```json
{
  "success": true,
  "message": "Job analytics retrieved successfully", 
  "data": {
    "job": {
      "id": 5,
      "title": "Frontend Developer",
      "created_at": "2025-01-01T00:00:00.000000Z",
      "status": "active"
    },
    "metrics": {
      "views": {
        "total": 450,
        "unique_views": 380,
        "returning_views": 70,
        "average_per_day": 15.0
      },
      "applications": {
        "total": 28,
        "pending": 12,
        "accepted": 3,
        "rejected": 13,
        "conversion_rate": 6.22
      },
      "engagement": {
        "average_time_on_page": 245,
        "bounce_rate": 35.2,
        "click_through_rate": 12.8
      }
    },
    "demographics": {
      "experience_levels": {
        "entry": 8,
        "mid": 15,
        "senior": 5
      },
      "locations": {
        "Ho Chi Minh City": 18,
        "Ha Noi": 7,
        "Da Nang": 3
      }
    },
    "timeline": {
      "daily_views": [
        {"date": "2025-01-01", "views": 15, "applications": 1},
        {"date": "2025-01-02", "views": 22, "applications": 3}
      ]
    },
    "comparisons": {
      "vs_user_average": {
        "views": 1.2,
        "applications": 1.8,
        "conversion_rate": 1.4
      },
      "vs_category_average": {
        "views": 0.9,
        "applications": 1.1,
        "conversion_rate": 1.3
      }
    }
  }
}
```

### 3. Get Trends
**GET** `/analytics/jobs/trends`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `period` (string, optional): Trend period (daily, weekly, monthly, default: daily)
- `date_from` (date, optional): Trends from date
- `date_to` (date, optional): Trends to date
- `metrics` (array, optional): Specific metrics (views, applications, conversion_rate)

**Response:**
```json
{
  "success": true,
  "message": "Job trends retrieved successfully",
  "data": {
    "period": "daily",
    "date_range": {
      "from": "2025-01-01",
      "to": "2025-01-07"
    },
    "trends": {
      "views": [
        {"period": "2025-01-01", "value": 120, "change": 0},
        {"period": "2025-01-02", "value": 145, "change": 20.83},
        {"period": "2025-01-03", "value": 132, "change": -8.97}
      ],
      "applications": [
        {"period": "2025-01-01", "value": 8, "change": 0},
        {"period": "2025-01-02", "value": 12, "change": 50.0},
        {"period": "2025-01-03", "value": 9, "change": -25.0}
      ],
      "conversion_rate": [
        {"period": "2025-01-01", "value": 6.67, "change": 0},
        {"period": "2025-01-02", "value": 8.28, "change": 24.11},
        {"period": "2025-01-03", "value": 6.82, "change": -17.63}
      ]
    },
    "summary": {
      "total_views": 397,
      "total_applications": 29,
      "average_conversion_rate": 7.30,
      "best_day": {
        "date": "2025-01-02",
        "views": 145,
        "applications": 12
      }
    }
  }
}
```

### 4. Compare Jobs
**POST** `/analytics/jobs/comparison`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "job_ids": [5, 12, 18],
  "metrics": ["views", "applications", "conversion_rate"],
  "date_from": "2025-01-01",
  "date_to": "2025-01-07"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Job comparison completed successfully",
  "data": {
    "comparison": [
      {
        "job": {
          "id": 5,
          "title": "Frontend Developer"
        },
        "metrics": {
          "views": 450,
          "applications": 28,
          "conversion_rate": 6.22
        },
        "rankings": {
          "views": 1,
          "applications": 1,
          "conversion_rate": 2
        }
      },
      {
        "job": {
          "id": 12,
          "title": "Backend Developer"
        },
        "metrics": {
          "views": 380,
          "applications": 19,
          "conversion_rate": 5.00
        },
        "rankings": {
          "views": 2,
          "applications": 2,
          "conversion_rate": 3
        }
      },
      {
        "job": {
          "id": 18,
          "title": "Full Stack Developer"
        },
        "metrics": {
          "views": 320,
          "applications": 25,
          "conversion_rate": 7.81
        },
        "rankings": {
          "views": 3,
          "applications": 1,
          "conversion_rate": 1
        }
      }
    ],
    "winner": {
      "overall": {
        "job_id": 5,
        "title": "Frontend Developer",
        "score": 8.5
      },
      "by_metric": {
        "views": {"job_id": 5, "value": 450},
        "applications": {"job_id": 5, "value": 28},
        "conversion_rate": {"job_id": 18, "value": 7.81}
      }
    }
  }
}
```

### 5. Get Performance Insights
**GET** `/analytics/jobs/insights`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `job_id` (integer, optional): Specific job ID for insights
- `period` (string, optional): Analysis period (week, month, quarter, default: month)

**Response:**
```json
{
  "success": true,
  "message": "Performance insights generated successfully",
  "data": {
    "insights": [
      {
        "type": "improvement_opportunity",
        "title": "Optimize Job Title for Better Visibility",
        "description": "Jobs with 'Senior' in the title get 23% more views",
        "impact": "high",
        "suggested_actions": [
          "Add experience level to job titles",
          "Use industry-standard terminology"
        ],
        "affected_jobs": [12, 15, 18]
      },
      {
        "type": "performance_alert",
        "title": "Low Conversion Rate Detected",
        "description": "Job #12 has 40% lower conversion than similar positions",
        "impact": "medium",
        "suggested_actions": [
          "Review job description clarity",
          "Adjust salary range",
          "Improve application process"
        ],
        "affected_jobs": [12]
      },
      {
        "type": "success_pattern",
        "title": "Featured Jobs Perform 2x Better",
        "description": "Your featured jobs get 2x more applications",
        "impact": "high",
        "suggested_actions": [
          "Consider featuring more important positions",
          "Feature jobs during peak application periods"
        ]
      }
    ],
    "recommendations": [
      {
        "category": "content_optimization",
        "priority": "high",
        "recommendations": [
          "Use action-oriented job titles",
          "Include salary ranges for transparency",
          "Add company benefits prominently"
        ]
      },
      {
        "category": "timing",
        "priority": "medium",
        "recommendations": [
          "Post jobs on Tuesday-Thursday for best visibility",
          "Feature urgent positions on weekdays"
        ]
      }
    ],
    "benchmarks": {
      "your_performance": {
        "average_views_per_job": 180,
        "average_applications_per_job": 12,
        "average_conversion_rate": 6.67
      },
      "industry_average": {
        "average_views_per_job": 220,
        "average_applications_per_job": 15,
        "average_conversion_rate": 6.82
      },
      "top_performers": {
        "average_views_per_job": 350,
        "average_applications_per_job": 28,
        "average_conversion_rate": 8.00
      }
    }
  }
}
```

---

## Bulk Operations APIs

### 1. Bulk Create Jobs
**POST** `/user/jobs/bulk/create`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "jobs": [
    {
      "title": "PHP Developer",
      "description": "Looking for PHP developer...",
      "company_name": "TechCorp",
      "job_location": "Ho Chi Minh City",
      "job_type": "full-time",
      "salary_min": 1500,
      "salary_max": 2500
    },
    {
      "title": "React Developer", 
      "description": "React developer needed...",
      "company_name": "StartupXYZ",
      "job_location": "Ha Noi",
      "job_type": "full-time",
      "salary_min": 2000,
      "salary_max": 3000
    }
  ],
  "default_values": {
    "experience_level": "mid",
    "is_featured": false,
    "categories": [102]
  }
}
```

**Response:**
```json
{
  "success": true,
  "message": "Bulk job creation completed",
  "data": {
    "operation_id": "bulk_create_20250101_123456",
    "total_requested": 2,
    "created": 2,
    "failed": 0,
    "created_jobs": [
      {
        "id": 28,
        "title": "PHP Developer",
        "status": "active"
      },
      {
        "id": 29,
        "title": "React Developer", 
        "status": "active"
      }
    ],
    "errors": []
  }
}
```

### 2. Bulk Update Jobs
**PUT** `/user/jobs/bulk/update`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "updates": [
    {
      "id": 28,
      "data": {
        "salary_max": 2800,
        "is_featured": true
      }
    },
    {
      "id": 29,
      "data": {
        "job_location": "Remote",
        "job_type": "contract"
      }
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Bulk job update completed",
  "data": {
    "operation_id": "bulk_update_20250101_123456",
    "total_requested": 2,
    "updated": 2,
    "failed": 0,
    "updated_jobs": [
      {
        "id": 28,
        "title": "PHP Developer",
        "changes": ["salary_max", "is_featured"]
      },
      {
        "id": 29,
        "title": "React Developer",
        "changes": ["job_location", "job_type"]
      }
    ],
    "errors": []
  }
}
```

### 3. Bulk Delete Jobs
**DELETE** `/user/jobs/bulk/delete`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "job_ids": [28, 29, 30],
  "confirm_deletion": true
}
```

**Response:**
```json
{
  "success": true,
  "message": "Bulk job deletion completed",
  "data": {
    "operation_id": "bulk_delete_20250101_123456",
    "total_requested": 3,
    "deleted": 2,
    "failed": 1,
    "deleted_jobs": [28, 29],
    "errors": [
      {
        "job_id": 30,
        "error": "Job not found or access denied"
      }
    ]
  }
}
```

### 4. Bulk Toggle Status
**PATCH** `/user/jobs/bulk/toggle-status`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "job_ids": [1, 2, 3],
  "status": "active", // "active" or "inactive" or "toggle"
  "reason": "Activating seasonal job postings"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Bulk status update completed",
  "data": {
    "operation_id": "bulk_status_20250101_123456",
    "total_requested": 3,
    "updated": 3,
    "failed": 0,
    "status_changes": [
      {
        "id": 1,
        "old_status": "inactive",
        "new_status": "active"
      },
      {
        "id": 2,
        "old_status": "inactive", 
        "new_status": "active"
      },
      {
        "id": 3,
        "old_status": "active",
        "new_status": "active"
      }
    ],
    "errors": []
  }
}
```

### 5. Bulk Duplicate Jobs
**POST** `/user/jobs/bulk/duplicate`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "job_ids": [1, 2],
  "modifications": {
    "title_suffix": " - Copy",
    "job_location": "Remote",
    "job_type": "contract"
  },
  "apply_to_all": true
}
```

**Response:**
```json
{
  "success": true,
  "message": "Bulk job duplication completed",
  "data": {
    "operation_id": "bulk_duplicate_20250101_123456",
    "total_requested": 2,
    "duplicated": 2,
    "failed": 0,
    "duplicated_jobs": [
      {
        "original_id": 1,
        "duplicate_id": 31,
        "title": "Senior PHP Developer - Copy"
      },
      {
        "original_id": 2,
        "duplicate_id": 32,
        "title": "Frontend Developer - Copy"
      }
    ],
    "errors": []
  }
}
```

### 6. Bulk Archive Jobs
**POST** `/user/jobs/bulk/archive`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "criteria": {
    "expired_jobs": true,
    "inactive_for_days": 30,
    "specific_job_ids": [15, 16, 17]
  },
  "archive_reason": "End of recruitment cycle"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Bulk job archiving completed",
  "data": {
    "operation_id": "bulk_archive_20250101_123456",
    "total_found": 8,
    "archived": 7,
    "failed": 1,
    "archived_jobs": [
      {
        "id": 15,
        "title": "Old PHP Position",
        "reason": "Expired job"
      },
      {
        "id": 16,
        "title": "Marketing Manager",
        "reason": "Inactive for 35 days"
      }
    ],
    "errors": [
      {
        "job_id": 20,
        "error": "Job has active applications"
      }
    ]
  }
}
```

### 7. Get Bulk Operation Status
**GET** `/user/jobs/bulk/status/{operationId}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Bulk operation status retrieved",
  "data": {
    "operation_id": "bulk_create_20250101_123456",
    "operation_type": "bulk_create",
    "status": "completed", // "processing", "completed", "failed", "cancelled"
    "progress": {
      "total": 100,
      "processed": 100,
      "successful": 95,
      "failed": 5,
      "percentage": 100
    },
    "started_at": "2025-01-01T12:34:56.000000Z",
    "completed_at": "2025-01-01T12:36:23.000000Z",
    "duration": 87, // seconds
    "summary": {
      "total_requested": 100,
      "successful": 95,
      "failed": 5,
      "error_rate": 5.0
    },
    "errors": [
      {
        "row": 23,
        "error": "Invalid email format",
        "data": {"contact_email": "invalid-email"}
      },
      {
        "row": 67,
        "error": "Missing required field: title"
      }
    ]
  }
}
```

---

## Import/Export APIs

### 1. Import Jobs
**POST** `/user/jobs/import`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
- `file` (file, required): CSV or Excel file
- `mapping` (json, optional): Field mapping object
- `skip_duplicates` (boolean, optional): Skip duplicate jobs (default: true)
- `validate_only` (boolean, optional): Only validate, don't import (default: false)

**Example:**
```
file: jobs_import.csv
mapping: {"Job Title": "title", "Company": "company_name", "Location": "job_location"}
skip_duplicates: true
validate_only: false
```

**Response:**
```json
{
  "success": true,
  "message": "Jobs imported successfully",
  "data": {
    "import_id": "import_20250101_123456",
    "imported": 85,
    "skipped": 10,
    "failed": 5,
    "total": 100,
    "errors": [
      {
        "row": 23,
        "errors": ["Job title is required"]
      },
      {
        "row": 67,
        "errors": ["Invalid email format", "Salary max must be greater than salary min"]
      }
    ]
  }
}
```

### 2. Export Jobs
**GET** `/user/jobs/export`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `format` (string, required): Export format (csv, excel, pdf)
- `job_ids` (array, optional): Specific job IDs to export
- `filters` (json, optional): Filters to apply
- `include_applications` (boolean, optional): Include application data
- `include_statistics` (boolean, optional): Include job statistics
- `date_from` (date, optional): Export jobs from date
- `date_to` (date, optional): Export jobs to date

**Example:**
```
GET /api/user/jobs/export?format=excel&include_statistics=true&date_from=2025-01-01
```

**Response (File Download):**
- Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
- Content-Disposition: attachment; filename="jobs_export_2025-01-01.xlsx"

**Response (JSON when requested):**
```json
{
  "success": true,
  "message": "Export completed",
  "data": {
    "format": "excel",
    "job_count": 25,
    "download_url": "http://localhost:8000/api/exports/jobs_export_2025-01-01.xlsx",
    "expires_at": "2025-01-02T12:34:56.000000Z"
  }
}
```

### 3. Export Jobs (POST with complex filters)
**POST** `/user/jobs/export`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "format": "pdf",
  "filters": {
    "job_type": ["full-time", "contract"],
    "location": ["Ho Chi Minh City", "Remote"],
    "salary_min": 2000,
    "is_featured": true,
    "categories": [102, 103]
  },
  "include_applications": true,
  "include_statistics": true,
  "date_range": {
    "from": "2025-01-01",
    "to": "2025-01-31"
  },
  "sort": {
    "field": "created_at",
    "direction": "desc"
  }
}
```

**Response:** Same as GET export response.

### 4. Download Import Template
**GET** `/user/jobs/import-template`

**Query Parameters:**
- `format` (string, optional): Template format (csv, excel, default: csv)
- `include_examples` (boolean, optional): Include example data (default: true)

**Response (File Download):**
- Content-Type: text/csv or application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
- Content-Disposition: attachment; filename="job_import_template_2025-01-01.csv"

### 5. Preview Import Data
**POST** `/user/jobs/import-preview`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
- `file` (file, required): CSV or Excel file
- `mapping` (json, optional): Field mapping object
- `rows_to_preview` (integer, optional): Number of rows to preview (max: 50, default: 10)

**Response:**
```json
{
  "success": true,
  "message": "Import preview generated successfully",
  "data": {
    "headers": ["Job Title", "Company", "Location", "Salary Min", "Salary Max"],
    "sample_data": [
      {
        "Job Title": "PHP Developer",
        "Company": "TechCorp",
        "Location": "Ho Chi Minh City",
        "Salary Min": "2000",
        "Salary Max": "3000"
      },
      {
        "Job Title": "React Developer",
        "Company": "StartupXYZ", 
        "Location": "Ha Noi",
        "Salary Min": "2500",
        "Salary Max": "3500"
      }
    ],
    "total_rows": 100,
    "suggested_mapping": {
      "Job Title": "title",
      "Company": "company_name",
      "Location": "job_location",
      "Salary Min": "salary_min",
      "Salary Max": "salary_max"
    },
    "validation_summary": {
      "valid_rows": 90,
      "invalid_rows": 10,
      "errors": [
        {
          "row": 23,
          "errors": ["Job title is required"]
        }
      ]
    }
  }
}
```

### 6. Get Import History
**GET** `/user/jobs/import-history`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `page` (integer, optional): Page number (default: 1)
- `per_page` (integer, optional): Items per page (max: 50, default: 15)

**Response:**
```json
{
  "success": true,
  "message": "Import history retrieved successfully",
  "data": {
    "imports": [
      {
        "id": 1,
        "import_id": "import_20250101_123456",
        "filename": "jobs_batch_1.csv",
        "total_rows": 100,
        "imported_rows": 85,
        "skipped_rows": 10,
        "failed_rows": 5,
        "success_rate": 85.0,
        "duration": "1m 23s",
        "status_text": "Partial Success",
        "created_at": "2025-01-01T12:34:56.000000Z",
        "errors": [
          {
            "row": 23,
            "errors": ["Job title is required"]
          }
        ]
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 25,
      "last_page": 2,
      "has_more": true
    }
  }
}
```

### 7. Get Field Mapping Options
**GET** `/user/jobs/field-mapping-options`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Field mapping options retrieved successfully",
  "data": {
    "required_fields": {
      "name": "Job Title/Name *",
      "description": "Job Description *",
      "location": "Location *",
      "salary_min": "Minimum Salary",
      "salary_max": "Maximum Salary"
    },
    "optional_fields": {
      "short_description": "Short Description",
      "requirements": "Job Requirements", 
      "benefits": "Benefits",
      "job_type": "Employment Type (full-time, part-time, contract)",
      "experience_level": "Experience Level (entry, mid, senior)",
      "skills": "Required Skills (comma separated)",
      "application_deadline": "Application Deadline (YYYY-MM-DD)",
      "status": "Status (active, inactive)",
      "is_featured": "Featured (yes/no)",
      "category": "Job Category",
      "company_name": "Company Name",
      "contact_email": "Contact Email"
    },
    "date_formats": ["Y-m-d", "d/m/Y", "m/d/Y", "Y-m-d H:i:s"],
    "boolean_values": {
      "true_values": ["yes", "true", "1", "active", "enabled"],
      "false_values": ["no", "false", "0", "inactive", "disabled"]
    }
  }
}
```

---

## Dashboard APIs

### 1. Get Dashboard Overview
**GET** `/dashboard`

**Headers:**
```
Authorization: Bearer {token}
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
        "company_name": "TechCorp Ltd",
        "status": "active",
        "applications_count": 12,
        "views_count": 245,
        "created_at": "2025-01-01T00:00:00.000000Z"
      }
    ],
    "recent_applications": [
      {
        "id": 1,
        "job_id": 5,
        "job_title": "Frontend Developer",
        "candidate_name": "John Doe",
        "candidate_email": "john@example.com",
        "status": "pending",
        "applied_at": "2025-01-01T00:00:00.000000Z"
      }
    ],
    "summary": {
      "total_jobs": 25,
      "active_jobs": 20,
      "total_applications": 150,
      "pending_applications": 45,
      "jobs_this_month": 8,
      "applications_this_month": 67
    }
  }
}
```

### 2. Get Job Applications
**GET** `/dashboard/jobs/{jobId}/applications`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `status` (string, optional): Filter by status (pending, accepted, rejected)
- `page` (integer, optional): Page number
- `per_page` (integer, optional): Items per page

**Response:**
```json
{
  "success": true,
  "message": "Job applications retrieved successfully",
  "data": {
    "job": {
      "id": 5,
      "title": "Frontend Developer",
      "total_applications": 28
    },
    "applications": [
      {
        "id": 1,
        "candidate_name": "John Doe",
        "candidate_email": "john@example.com",
        "phone": "+84123456789",
        "cv_file": "http://localhost:8000/storage/cv/john_doe_cv.pdf",
        "cover_letter": "I am very interested in this position...",
        "status": "pending",
        "experience_years": 3,
        "current_salary": 2000,
        "expected_salary": 2500,
        "applied_at": "2025-01-01T00:00:00.000000Z",
        "status_updated_at": null
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 28,
      "last_page": 2
    },
    "status_summary": {
      "pending": 12,
      "accepted": 3,
      "rejected": 13
    }
  }
}
```

### 3. Update Application Status
**PUT** `/dashboard/applications/{applicationId}/status`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "status": "accepted", // "pending", "accepted", "rejected", "shortlisted"
  "notes": "Great candidate, schedule interview",
  "feedback": "Strong technical skills, good communication"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Application status updated successfully",
  "data": {
    "application_id": 1,
    "candidate_name": "John Doe",
    "old_status": "pending",
    "new_status": "accepted",
    "updated_at": "2025-01-01T00:00:00.000000Z",
    "notes": "Great candidate, schedule interview",
    "feedback": "Strong technical skills, good communication"
  }
}
```

---

## Job Application APIs

### 1. Apply for Job
**POST** `/jobs/{jobId}/apply`

**Headers:**
```
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
- `candidate_name` (string, required): Full name
- `candidate_email` (email, required): Email address
- `phone` (string, optional): Phone number
- `cv_file` (file, required): CV file (PDF, DOC, DOCX)
- `cover_letter` (text, optional): Cover letter
- `experience_years` (integer, optional): Years of experience
- `current_salary` (number, optional): Current salary
- `expected_salary` (number, optional): Expected salary
- `portfolio_url` (url, optional): Portfolio URL
- `linkedin_url` (url, optional): LinkedIn profile

**Response:**
```json
{
  "success": true,
  "message": "Application submitted successfully",
  "data": {
    "application_id": 1,
    "job_id": 5,
    "job_title": "Frontend Developer",
    "candidate_name": "John Doe",
    "candidate_email": "john@example.com",
    "status": "pending",
    "applied_at": "2025-01-01T00:00:00.000000Z",
    "tracking_code": "APP-20250101-001"
  }
}
```

### 2. Get User Applications (Authenticated)
**GET** `/applications`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `status` (string, optional): Filter by status
- `page` (integer, optional): Page number
- `per_page` (integer, optional): Items per page

**Response:**
```json
{
  "success": true,
  "message": "User applications retrieved successfully",
  "data": {
    "applications": [
      {
        "id": 1,
        "job_id": 5,
        "job_title": "Frontend Developer",
        "company_name": "TechCorp Ltd",
        "status": "pending",
        "applied_at": "2025-01-01T00:00:00.000000Z",
        "status_updated_at": null,
        "tracking_code": "APP-20250101-001"
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 5,
      "last_page": 1
    },
    "status_summary": {
      "pending": 2,
      "accepted": 1,
      "rejected": 2
    }
  }
}
```

### 3. Get Application Status
**GET** `/applications/{applicationId}/status`

**Query Parameters:**
- `email` (email, optional): Applicant email for verification

**Response:**
```json
{
  "success": true,
  "message": "Application status retrieved successfully",
  "data": {
    "application_id": 1,
    "job_title": "Frontend Developer",
    "company_name": "TechCorp Ltd",
    "status": "shortlisted",
    "status_text": "Shortlisted for Interview",
    "applied_at": "2025-01-01T00:00:00.000000Z",
    "status_updated_at": "2025-01-03T00:00:00.000000Z",
    "tracking_code": "APP-20250101-001",
    "next_steps": "You will be contacted within 5 business days for interview scheduling.",
    "feedback": "Great technical background. Moving to next round."
  }
}
```

---

## Error Responses

### Standard Error Format
All API endpoints return errors in a consistent format:

```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field_name": ["Field specific error message"]
  }
}
```

### HTTP Status Codes

- **200 OK**: Successful request
- **201 Created**: Resource created successfully
- **204 No Content**: Successful request with no content to return
- **400 Bad Request**: Invalid request data
- **401 Unauthorized**: Authentication required
- **403 Forbidden**: Access denied
- **404 Not Found**: Resource not found
- **422 Unprocessable Entity**: Validation errors
- **429 Too Many Requests**: Rate limit exceeded
- **500 Internal Server Error**: Server error

### Common Error Examples

**Validation Error (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."],
    "salary_max": ["The salary max must be greater than or equal to salary min."]
  }
}
```

**Authentication Error (401):**
```json
{
  "success": false,
  "message": "Unauthenticated",
  "errors": {
    "authentication": ["Please provide a valid bearer token."]
  }
}
```

**Rate Limit Error (429):**
```json
{
  "success": false,
  "message": "Too many requests",
  "errors": {
    "rate_limit": ["You have exceeded the rate limit. Please try again later."]
  }
}
```

**Not Found Error (404):**
```json
{
  "success": false,
  "message": "Job not found",
  "errors": {
    "job": ["The requested job does not exist or you don't have access to it."]
  }
}
```

---

## Rate Limits

- **Public APIs**: 60 requests per minute
- **Authenticated APIs**: 60 requests per minute
- **Bulk Operations**: 30 requests per minute
- **Import/Export**: 20 requests per minute
- **File Upload**: 10 requests per minute

## File Upload Limits

- **CV Files**: Max 5MB (PDF, DOC, DOCX)
- **Import Files**: Max 10MB (CSV, XLSX, XLS)
- **Image Files**: Max 2MB (JPG, PNG, GIF)

## Pagination

All list endpoints support pagination with the following parameters:
- `page`: Page number (default: 1)
- `per_page`: Items per page (max: 50, default: 15)

Pagination response includes:
- `current_page`: Current page number
- `per_page`: Items per page
- `total`: Total items
- `last_page`: Last page number
- `has_more`: Whether there are more pages

## Filtering and Sorting

Many endpoints support filtering and sorting:
- Use query parameters for simple filters
- Use POST body for complex filters
- `order_by`: Field to sort by
- `order_direction`: Sort direction (asc/desc)

## Field Selection

Some endpoints support field selection to reduce response size:
- `fields`: Comma-separated list of fields to include
- `exclude`: Comma-separated list of fields to exclude

Example: `?fields=id,title,salary_min,salary_max&exclude=description`