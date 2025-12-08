# API Job Options - Tài liệu Chi tiết

## Tổng quan

API này cung cấp endpoint chính để load tất cả options/thuộc tính của job dùng cho form tạo và chỉnh sửa job tại màn hình `/admin/jobs/create`.

**Base URL**: `/api/jobs/options`

**Rate Limit**: 120 requests/phút

**Authentication**: Không yêu cầu (Public API)

**Số lượng API**: 2 endpoints
- `/form-data` - API chính load tất cả options
- `/search` - API tìm kiếm với autocomplete

---

## 1. API Chính - Load Tất Cả Options Cho Form

### Endpoint: GET `/api/jobs/options/form-data`

API tổng hợp để load tất cả dữ liệu cần thiết cho form tạo job trong một request duy nhất.

#### Request

```http
GET /api/jobs/options/form-data
```

#### Response Success (200)

```json
{
  "success": true,
  "message": "Job form data retrieved successfully",
  "data": {
    "attributes": {
      "job_type": {
        "code": "job_type",
        "name": "Loại Job",
        "type": "select",
        "is_required": true,
        "is_filterable": true,
        "options": [
          {
            "id": 1,
            "value": "Full-time",
            "sort_order": 1
          },
          {
            "id": 2,
            "value": "Part-time",
            "sort_order": 2
          },
          {
            "id": 3,
            "value": "Contract",
            "sort_order": 3
          },
          {
            "id": 4,
            "value": "Freelance",
            "sort_order": 4
          }
        ]
      },
      "experience_level": {
        "code": "experience_level",
        "name": "Cấp độ kinh nghiệm",
        "type": "select",
        "is_required": true,
        "is_filterable": true,
        "options": [
          {
            "id": 5,
            "value": "Intern",
            "sort_order": 1
          },
          {
            "id": 6,
            "value": "Fresher",
            "sort_order": 2
          },
          {
            "id": 7,
            "value": "Junior",
            "sort_order": 3
          },
          {
            "id": 8,
            "value": "Middle",
            "sort_order": 4
          },
          {
            "id": 9,
            "value": "Senior",
            "sort_order": 5
          },
          {
            "id": 10,
            "value": "Lead",
            "sort_order": 6
          }
        ]
      },
      "job_location": {
        "code": "job_location",
        "name": "Địa điểm làm việc",
        "type": "text",
        "is_required": true,
        "is_filterable": true,
        "options": []
      },
      "application_method": {
        "code": "application_method",
        "name": "Phương thức ứng tuyển",
        "type": "select",
        "is_required": true,
        "is_filterable": false,
        "options": [
          {
            "id": 11,
            "value": "Qua email",
            "sort_order": 1
          },
          {
            "id": 12,
            "value": "Qua website",
            "sort_order": 2
          },
          {
            "id": 13,
            "value": "Qua form ứng tuyển",
            "sort_order": 3
          }
        ]
      },
      "education_level": {
        "code": "education_level",
        "name": "Trình độ học vấn",
        "type": "select",
        "is_required": false,
        "is_filterable": true,
        "options": [
          {
            "id": 14,
            "value": "Trung học",
            "sort_order": 1
          },
          {
            "id": 15,
            "value": "Trung cấp",
            "sort_order": 2
          },
          {
            "id": 16,
            "value": "Cao đẳng",
            "sort_order": 3
          },
          {
            "id": 17,
            "value": "Đại học",
            "sort_order": 4
          },
          {
            "id": 18,
            "value": "Thạc sĩ",
            "sort_order": 5
          },
          {
            "id": 19,
            "value": "Tiến sĩ",
            "sort_order": 6
          }
        ]
      },
      "english_level": {
        "code": "english_level",
        "name": "Trình độ tiếng Anh",
        "type": "select",
        "is_required": false,
        "is_filterable": true,
        "options": [
          {
            "id": 20,
            "value": "Không yêu cầu",
            "sort_order": 1
          },
          {
            "id": 21,
            "value": "Cơ bản",
            "sort_order": 2
          },
          {
            "id": 22,
            "value": "Trung bình",
            "sort_order": 3
          },
          {
            "id": 23,
            "value": "Khá",
            "sort_order": 4
          },
          {
            "id": 24,
            "value": "Tốt",
            "sort_order": 5
          },
          {
            "id": 25,
            "value": "Thành thạo",
            "sort_order": 6
          }
        ]
      },
      "company_size": {
        "code": "company_size",
        "name": "Quy mô công ty",
        "type": "select",
        "is_required": false,
        "is_filterable": true,
        "options": [
          {
            "id": 26,
            "value": "1-10 nhân viên",
            "sort_order": 1
          },
          {
            "id": 27,
            "value": "11-50 nhân viên",
            "sort_order": 2
          },
          {
            "id": 28,
            "value": "51-200 nhân viên",
            "sort_order": 3
          },
          {
            "id": 29,
            "value": "201-500 nhân viên",
            "sort_order": 4
          },
          {
            "id": 30,
            "value": "500+ nhân viên",
            "sort_order": 5
          }
        ]
      },
      "salary_range": {
        "code": "salary_range",
        "name": "Mức lương",
        "type": "select",
        "is_required": false,
        "is_filterable": true,
        "options": [
          {
            "id": 31,
            "value": "Dưới 10 triệu",
            "sort_order": 1
          },
          {
            "id": 32,
            "value": "10-15 triệu",
            "sort_order": 2
          },
          {
            "id": 33,
            "value": "15-20 triệu",
            "sort_order": 3
          },
          {
            "id": 34,
            "value": "20-30 triệu",
            "sort_order": 4
          },
          {
            "id": 35,
            "value": "30-50 triệu",
            "sort_order": 5
          },
          {
            "id": 36,
            "value": "Trên 50 triệu",
            "sort_order": 6
          },
          {
            "id": 37,
            "value": "Thỏa thuận",
            "sort_order": 7
          }
        ]
      },
      "required_skills": {
        "code": "required_skills",
        "name": "Kỹ năng yêu cầu",
        "type": "multiselect",
        "is_required": false,
        "is_filterable": true,
        "options": []
      },
      "job_benefits": {
        "code": "job_benefits",
        "name": "Phúc lợi",
        "type": "multiselect",
        "is_required": false,
        "is_filterable": false,
        "options": []
      }
    },
    "categories": [
      {
        "id": 103,
        "name": "Công nghệ thông tin",
        "slug": "cong-nghe-thong-tin",
        "job_count": 45,
        "position": 1
      },
      {
        "id": 104,
        "name": "Marketing",
        "slug": "marketing",
        "job_count": 23,
        "position": 2
      },
      {
        "id": 105,
        "name": "Thiết kế",
        "slug": "thiet-ke",
        "job_count": 18,
        "position": 3
      }
    ],
    "popular_skills": [
      {
        "value": "PHP",
        "count": 35
      },
      {
        "value": "Laravel",
        "count": 30
      },
      {
        "value": "JavaScript",
        "count": 42
      }
    ],
    "common_benefits": [
      {
        "value": "Bảo hiểm sức khỏe",
        "count": 50
      },
      {
        "value": "Thưởng tháng 13",
        "count": 45
      }
    ],
    "application_methods": [
      {
        "id": 11,
        "value": "Qua email",
        "sort_order": 1
      },
      {
        "id": 12,
        "value": "Qua website",
        "sort_order": 2
      }
    ]
  }
}
```

#### Response Error (500)

```json
{
  "success": false,
  "message": "Failed to retrieve job form data",
  "error": "Internal server error"
}
```

---

## 2. API Tìm Kiếm Options (Autocomplete)

### Endpoint: GET `/api/jobs/options/search`

Tìm kiếm đồng thời trên nhiều loại options.

#### Request

```http
GET /api/jobs/options/search?query=php&types[]=skills&types[]=companies&limit=10
```

#### Query Parameters

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| query | string | Yes | - | Từ khóa tìm kiếm (min: 2 ký tự) |
| types | array | No | ['skills', 'companies'] | Các loại options cần tìm |
| limit | integer | No | 10 | Số lượng kết quả mỗi loại (max: 50) |

#### Validation Rules

- `query`: required, string, min:2
- `types`: nullable, array
- `types.*`: string, in:skills,companies,benefits
- `limit`: nullable, integer, min:1, max:50

#### Response Success (200)

```json
{
  "success": true,
  "message": "Search completed successfully",
  "data": {
    "skills": [
      {
        "value": "PHP",
        "count": 35
      },
      {
        "value": "PHPUnit",
        "count": 12
      }
    ],
    "companies": [
      {
        "value": "PHP Solutions Co.",
        "job_count": 5
      }
    ]
  },
  "query": "php"
}
```

#### Response Error (422)

```json
{
  "message": "The query field is required.",
  "errors": {
    "query": [
      "The query field is required."
    ]
  }
}
```

---

## Mapping Thuộc Tính Job

### Bảng Mapping Các Thuộc Tính

| Tên Hiển Thị | Attribute Code | Type | Required | API Endpoint |
|--------------|----------------|------|----------|--------------|
| Loại Job | `job_type` | select | Yes | `/form-data` hoặc `/filter-options` |
| Cấp độ kinh nghiệm | `experience_level` | select | Yes | `/form-data` hoặc `/filter-options` |
| Địa điểm làm việc | `job_location` | text | Yes | `/form-data` (autocomplete từ data có sẵn) |
| Phương thức ứng tuyển | `application_method` | select | Yes | `/form-data` hoặc `/filter-options` |
| Trình độ học vấn | `education_level` | select | No | `/form-data` hoặc `/filter-options` |
| Trình độ tiếng Anh | `english_level` | select | No | `/form-data` hoặc `/filter-options` |
| Quy mô công ty | `company_size` | select | No | `/form-data` hoặc `/filter-options` |
| Mức lương | `salary_range` | select | No | `/salary-ranges` |
| Kỹ năng yêu cầu | `required_skills` | multiselect | No | `/skills` |
| Phúc lợi | `job_benefits` | multiselect | No | `/benefits` |

---

## Caching Strategy

Tất cả các API đều sử dụng cache để tối ưu performance:

- **Cache Time**: 3600 giây (1 giờ) cho dữ liệu tĩnh
- **Cache Time**: 1800 giây (30 phút) cho dữ liệu động (skills, companies, benefits)
- **Cache Key Pattern**: 
  - `job_filter_options` - Tất cả filter options
  - `job_form_attributes` - Attributes cho form
  - `job_categories` - Danh sách categories
  - `job_skills_{hash}` - Skills với search params
  - `job_companies_{hash}` - Companies với search params
  - `job_benefits_{hash}` - Benefits với search params

---

## Implementation Details

### Controller

**File**: `app/Http/Controllers/Api/JobOptionsController.php`

Xử lý tất cả các request liên quan đến job options.

### Service Layer

**File**: `app/Services/JobFilterService.php`

Chứa business logic để:
- Lấy dữ liệu từ database
- Xử lý cache
- Format dữ liệu response
- Tổng hợp dữ liệu từ nhiều nguồn

### Data Sources

1. **Attribute Options**: Từ bảng `attribute_options` và `attribute_option_translations`
2. **Product Attributes**: Từ bảng `product_attribute_values`
3. **Categories**: Từ bảng `categories` và `category_translations`
4. **Predefined Data**: Danh sách địa điểm Việt Nam, skills phổ biến, benefits thông dụng

---

## Error Handling

Tất cả API đều có error handling thống nhất:

### Success Response Format

```json
{
  "success": true,
  "message": "Success message",
  "data": {...}
}
```

### Error Response Format

```json
{
  "success": false,
  "message": "Error message",
  "error": "Detailed error (only in debug mode)"
}
```

### HTTP Status Codes

- `200 OK`: Request thành công
- `422 Unprocessable Entity`: Validation error
- `500 Internal Server Error`: Server error

---

## Usage Example - Frontend Integration

### Khởi tạo form tạo job

```javascript
// Load tất cả options cần thiết cho form
async function initJobForm() {
  try {
    const response = await fetch('/api/jobs/options/form-data');
    const result = await response.json();
    
    if (result.success) {
      const { attributes, categories, popular_skills, common_benefits } = result.data;
      
      // Populate select options
      populateSelect('job_type', attributes.job_type.options);
      populateSelect('experience_level', attributes.experience_level.options);
      populateSelect('education_level', attributes.education_level.options);
      populateSelect('english_level', attributes.english_level.options);
      populateSelect('company_size', attributes.company_size.options);
      populateSelect('application_method', attributes.application_method.options);
      populateSelect('salary_range', attributes.salary_range.options);
      
      // Initialize autocomplete for skills
      initSkillsAutocomplete(popular_skills);
      
      // Initialize benefits multiselect
      initBenefitsMultiselect(common_benefits);
      
      // Initialize categories
      initCategories(categories);
    }
  } catch (error) {
    console.error('Failed to load form data:', error);
  }
}

// Search với autocomplete
let searchTimeout;
async function searchOptions(query, types = ['skills', 'benefits']) {
  clearTimeout(searchTimeout);
  
  searchTimeout = setTimeout(async () => {
    const params = new URLSearchParams({
      query: query,
      limit: 20
    });
    
    types.forEach(type => params.append('types[]', type));
    
    const response = await fetch(`/api/jobs/options/search?${params}`);
    const result = await response.json();
    
    if (result.success) {
      updateSuggestions(result.data);
    }
  }, 300);
}
```

---

## Performance Considerations

1. **Chỉ sử dụng 1 API call** - `/form-data` load tất cả options trong 1 request
2. **Cache được enable** - Tất cả data được cache 1 giờ
3. **Rate limiting**: 120 requests/phút
4. **Lazy search**: Sử dụng `/search` API khi cần autocomplete
5. **Debounce search**: Delay 300ms trước khi gọi API search

---

## Notes

- Tất cả text đều có hỗ trợ đa ngôn ngữ (hiện tại: Tiếng Việt)
- Options được sắp xếp theo `sort_order`
- Job count được tính real-time từ database
- Predefined data (locations, skills, benefits) được merge với data từ database

---

## Changelog

### Version 2.0.0 (2025-12-08)
- **BREAKING CHANGE**: Dọn dẹp và xóa các API trùng lặp
- Chỉ giữ lại 2 endpoints chính:
  - `/form-data` - API chính load tất cả options
  - `/search` - API tìm kiếm với autocomplete
- Xóa các endpoints không cần thiết:
  - `/filter-options`
  - `/skills`
  - `/companies`
  - `/benefits`
  - `/salary-ranges`
  - `/industries`
  - `/popular-keywords`
- Tối ưu performance và giảm confusion

### Version 1.0.0 (2025-12-08)
- Initial API documentation
- Hỗ trợ 7 thuộc tính chính của job
- Cache strategy implementation
- Rate limiting setup
