# Job Management API - Tối Ưu Hóa

## Tổng Quan Hệ Thống

### Cấu Trúc Database
```
products (Core job data)
├── product_flat (Localized & optimized data)
├── product_attribute_values (Job-specific attributes)
├── product_categories (Job categorization)
├── product_images (Job thumbnails)
└── job_applications (Applications)
```

### Job Attributes Schema
```json
{
  "job_type": "select",           // ID: 40 (Full-time, Part-time, etc.)
  "experience_level": "select",   // ID: 41 (Fresher, Junior, Senior, etc.)
  "salary_range": "select",       // ID: 42 (Dưới 10 triệu, 10-20 triệu, etc.)
  "job_location": "select",       // ID: 43 (Hà Nội, HCM, Remote, etc.)
  "required_skills": "multiselect", // ID: 45 (Unity, C#, JavaScript, etc.)
  "job_benefits": "multiselect",  // ID: 48 (Bảo hiểm, Du lịch, etc.)
  "contact_email": "text",        // ID: 50
  "contact_phone": "text"         // ID: 51
}
```

## API Endpoints Tối Ưu

### 1. Job Creation API (Optimized)

**POST** `/api/jobs`

```json
{
  "title": "Unity Developer",
  "company_name": "Game Studio ABC",
  "description": "HTML content...",
  "short_description": "Brief summary...",
  "job_type": 62,                    // Full-time ID
  "experience_level": 71,            // Middle (3-5 năm) ID
  "salary_range": 78,                // 30-50 triệu ID
  "job_location": 82,                // Hồ Chí Minh ID
  "required_skills": [95, 97, 111],  // Unity, C#, Git IDs
  "job_benefits": [125, 127, 131],   // Bảo hiểm, Thưởng, Remote IDs
  "contact_email": "hr@gamestudio.com",
  "contact_phone": "0901234567",
  "application_deadline": "2025-12-31",
  "is_urgent": true,
  "is_featured": false,
  "categories": [102],               // Job category ID
  "thumbnail": "base64_image_data"   // Optional
}
```

**Response:**
```json
{
  "success": true,
  "message": "Job created successfully",
  "data": {
    "id": 26,
    "sku": "JOB_GAMESTUDIOABC_UNITY_DEVELOPER_2025",
    "url_key": "unity-developer-game-studio-abc-26",
    "title": "Unity Developer",
    "company_name": "Game Studio ABC",
    "salary_formatted": "30-50 triệu VND",
    "location": "Hồ Chí Minh",
    "created_at": "2025-11-06T07:46:35Z"
  }
}
```

### 2. Job Options API (Form Data)

**GET** `/api/jobs/options/form-data`

```json
{
  "success": true,
  "data": {
    "job_types": [
      {"id": 62, "label": "Full-time"},
      {"id": 63, "label": "Part-time"},
      {"id": 64, "label": "Contract"}
    ],
    "experience_levels": [
      {"id": 69, "label": "Fresher (0-1 năm)"},
      {"id": 70, "label": "Junior (1-3 năm)"},
      {"id": 71, "label": "Middle (3-5 năm)"}
    ],
    "salary_ranges": [
      {"id": 75, "label": "Dưới 10 triệu"},
      {"id": 76, "label": "10-20 triệu"},
      {"id": 77, "label": "20-30 triệu"}
    ],
    "locations": [
      {"id": 82, "label": "Hồ Chí Minh"},
      {"id": 83, "label": "Hà Nội"},
      {"id": 88, "label": "Remote"}
    ],
    "skills": [
      {"id": 95, "label": "Unity"},
      {"id": 96, "label": "Unreal Engine"},
      {"id": 97, "label": "C#"}
    ],
    "benefits": [
      {"id": 125, "label": "Bảo hiểm sức khỏe"},
      {"id": 126, "label": "Bảo hiểm xã hội"},
      {"id": 127, "label": "Thưởng hiệu suất"}
    ],
    "categories": [
      {"id": 102, "name": "Việc Làm", "slug": "viec-lam"}
    ]
  }
}
```

### 3. Job Update API (Optimized)

**PUT** `/api/jobs/{id}`

```json
{
  "title": "Senior Unity Developer",
  "experience_level": 72,            // Senior (5+ năm)
  "salary_range": 79,                // 50-80 triệu
  "required_skills": [95, 97, 98, 111], // Add C++
  "is_urgent": false,
  "application_deadline": "2025-12-15"
}
```

### 4. Job Listing API (Enhanced)

**GET** `/api/jobs?search=unity&job_type=62&location=82&per_page=10`

```json
{
  "success": true,
  "data": [
    {
      "id": 25,
      "title": "Unity Developer",
      "company_name": "Game Studio ABC",
      "url_key": "unity-developer-game-studio-abc-25",
      "thumbnail_url": "https://example.com/thumb.jpg",
      "salary_formatted": "30-50 triệu VND",
      "location": "Hồ Chí Minh",
      "job_type": "Full-time",
      "experience_level": "Middle (3-5 năm)",
      "skills": ["Unity", "C#", "Git"],
      "is_urgent": true,
      "is_featured": false,
      "posted_ago": "2 ngày trước",
      "applications_count": 15
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 45,
    "last_page": 5
  }
}
```

### 5. Job Analytics API

**GET** `/api/analytics/jobs/{id}/analytics`

```json
{
  "success": true,
  "data": {
    "job_id": 25,
    "views": 1250,
    "applications": 15,
    "conversion_rate": 1.2,
    "daily_stats": [
      {"date": "2025-11-01", "views": 45, "applications": 2},
      {"date": "2025-11-02", "views": 38, "applications": 1}
    ],
    "top_referrers": [
      {"source": "google", "count": 450},
      {"source": "facebook", "count": 200}
    ]
  }
}
```

## Optimized Database Queries

### 1. Job Creation Query
```sql
-- Single transaction with optimized inserts
INSERT INTO products (sku, type, attribute_family_id) VALUES (?, 'simple', 1);
INSERT INTO product_attribute_values (product_id, attribute_id, text_value, integer_value) VALUES 
  (?, 40, NULL, 62),  -- job_type
  (?, 41, NULL, 71),  -- experience_level
  (?, 50, 'hr@company.com', NULL); -- contact_email
```

### 2. Job Listing Query (Optimized)
```sql
SELECT p.id, pf.name, pf.price, pf.url_key,
       GROUP_CONCAT(aot_skills.label) as skills,
       aot_location.label as location,
       aot_type.label as job_type
FROM products p
JOIN product_flat pf ON p.id = pf.product_id AND pf.locale = 'vi'
LEFT JOIN product_attribute_values pav_skills ON p.id = pav_skills.product_id AND pav_skills.attribute_id = 45
LEFT JOIN attribute_options ao_skills ON FIND_IN_SET(ao_skills.id, pav_skills.text_value)
LEFT JOIN attribute_option_translations aot_skills ON ao_skills.id = aot_skills.attribute_option_id
WHERE p.sku LIKE 'JOB_%' AND pf.status = 1
GROUP BY p.id
ORDER BY p.created_at DESC;
```

## API Response Optimization

### 1. Cached Responses
```php
// Cache job options for 1 hour
Cache::remember('job_options', 3600, function() {
    return $this->getJobOptions();
});
```

### 2. Eager Loading
```php
// Optimize N+1 queries
Product::with([
    'attribute_values.attribute',
    'categories.translations',
    'images'
])->where('sku', 'LIKE', 'JOB_%')->get();
```

### 3. Database Indexing
```sql
-- Recommended indexes
CREATE INDEX idx_products_sku_type ON products(sku, type);
CREATE INDEX idx_product_flat_status_locale ON product_flat(status, locale, visible_individually);
CREATE INDEX idx_product_attribute_values_lookup ON product_attribute_values(product_id, attribute_id);
```

## Error Handling & Validation

### 1. Validation Rules
```php
'title' => 'required|string|max:255',
'company_name' => 'required|string|max:255',
'job_type' => 'required|exists:attribute_options,id',
'experience_level' => 'required|exists:attribute_options,id',
'salary_range' => 'required|exists:attribute_options,id',
'contact_email' => 'required|email|max:255',
'required_skills' => 'array|exists:attribute_options,id',
'application_deadline' => 'required|date|after:today'
```

### 2. Error Responses
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "job_type": ["The selected job type is invalid."],
    "contact_email": ["The contact email field is required."]
  }
}
```

## Performance Metrics

- **Job Creation**: < 200ms
- **Job Listing**: < 100ms (with pagination)
- **Job Search**: < 150ms (with filters)
- **Options API**: < 50ms (cached)

## Security Features

1. **Rate Limiting**: 60 requests/minute
2. **Input Sanitization**: HTML purification
3. **SQL Injection Prevention**: Eloquent ORM
4. **CSRF Protection**: Token validation
5. **File Upload Security**: Type & size validation
