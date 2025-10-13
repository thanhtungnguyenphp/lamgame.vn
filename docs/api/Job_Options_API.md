# Job Options API Documentation

Các API endpoint để load dữ liệu dropdown và filter options cho job posting forms.

## Base URL
```
GET /api/jobs/options/
```

## Authentication
Tất cả các endpoint này là **public** (không cần authentication).

## Rate Limiting
- 120 requests per minute per IP

## Available Endpoints

### 1. Get All Filter Options
```http
GET /api/jobs/options/filter-options
```

Trả về tất cả options cho search/filter forms.

**Response:**
```json
{
  "success": true,
  "message": "Filter options retrieved successfully",
  "data": {
    "job_types": [
      {
        "id": 1,
        "value": "Toàn thời gian",
        "label": "Toàn thời gian",
        "sort_order": 1
      }
    ],
    "experience_levels": [...],
    "salary_ranges": [...],
    "categories": [...],
    "locations": [...],
    "popular_skills": [...],
    "common_benefits": [...]
  }
}
```

### 2. Get Job Form Data
```http
GET /api/jobs/options/form-data
```

Endpoint tổng hợp cho form tạo job posting (optimize số lượng request).

**Response:**
```json
{
  "success": true,
  "message": "Job form data retrieved successfully",
  "data": {
    "attributes": {
      "job_type": {
        "code": "job_type",
        "name": "Loại hình công việc",
        "type": "select",
        "is_required": true,
        "options": [...]
      }
    },
    "categories": [...],
    "locations": [...],
    "popular_skills": [...],
    "common_benefits": [...],
    "application_methods": [...]
  }
}
```

### 3. Get Locations
```http
GET /api/jobs/options/locations
```

**Query Parameters:**
- `search` (string, optional): Tìm kiếm theo tên địa điểm
- `limit` (integer, optional): Giới hạn số lượng kết quả (mặc định: 50, tối đa: 100)

**Response:**
```json
{
  "success": true,
  "message": "Locations retrieved successfully",
  "data": [
    {
      "id": null,
      "value": "TP.HCM",
      "label": "TP.HCM",
      "count": 150
    },
    {
      "id": null,
      "value": "Hà Nội",
      "label": "Hà Nội", 
      "count": 120
    }
  ],
  "total": 63
}
```

### 4. Get Skills
```http
GET /api/jobs/options/skills
```

**Query Parameters:**
- `search` (string, optional): Tìm kiếm theo tên skill
- `category` (string, optional): Lọc theo category (IT, Marketing, Design)
- `limit` (integer, optional): Giới hạn số lượng (mặc định: 50, tối đa: 100)

**Response:**
```json
{
  "success": true,
  "message": "Skills retrieved successfully", 
  "data": [
    {
      "id": null,
      "value": "PHP",
      "label": "PHP",
      "count": 85
    },
    {
      "id": null,
      "value": "Laravel",
      "label": "Laravel",
      "count": 72
    }
  ],
  "total": 45
}
```

### 5. Get Companies
```http
GET /api/jobs/options/companies
```

**Query Parameters:**
- `search` (string, optional): Tìm kiếm theo tên công ty
- `limit` (integer, optional): Giới hạn số lượng (mặc định: 50, tối đa: 100)

**Response:**
```json
{
  "success": true,
  "message": "Companies retrieved successfully",
  "data": [
    {
      "id": null,
      "value": "FPT Software",
      "label": "FPT Software",
      "count": 25
    }
  ],
  "total": 120
}
```

### 6. Get Benefits
```http
GET /api/jobs/options/benefits
```

**Query Parameters:**
- `search` (string, optional): Tìm kiếm theo benefit
- `limit` (integer, optional): Giới hạn số lượng (mặc định: 50, tối đa: 100)

**Response:**
```json
{
  "success": true,
  "message": "Benefits retrieved successfully",
  "data": [
    {
      "id": null,
      "value": "Bảo hiểm sức khỏe", 
      "label": "Bảo hiểm sức khỏe",
      "count": 95
    }
  ],
  "total": 16
}
```

### 7. Get Salary Ranges
```http
GET /api/jobs/options/salary-ranges
```

**Response:**
```json
{
  "success": true,
  "message": "Salary ranges retrieved successfully",
  "data": [
    {
      "id": 10,
      "value": "10-20 triệu",
      "label": "10-20 triệu", 
      "sort_order": 2,
      "count": 45
    }
  ]
}
```

### 8. Get Industries
```http
GET /api/jobs/options/industries
```

**Response:**
```json
{
  "success": true,
  "message": "Industries retrieved successfully",
  "data": [
    {
      "id": 103,
      "value": "IT - Phần mềm",
      "label": "IT - Phần mềm",
      "slug": "it-phan-mem",
      "count": 150,
      "sort_order": 1
    }
  ]
}
```

### 9. Get Popular Keywords
```http
GET /api/jobs/options/popular-keywords
```

**Response:**
```json
{
  "success": true,
  "message": "Popular keywords retrieved successfully",
  "data": [
    {
      "keyword": "PHP",
      "count": 150
    },
    {
      "keyword": "JavaScript", 
      "count": 200
    }
  ]
}
```

### 10. Search Options
```http
GET /api/jobs/options/search
```

**Query Parameters:**
- `query` (string, required): Từ khóa tìm kiếm (tối thiểu 2 ký tự)
- `types` (array, optional): Các loại cần search ['skills', 'locations', 'companies', 'benefits']
- `limit` (integer, optional): Giới hạn kết quả cho mỗi loại (mặc định: 10, tối đa: 50)

**Example:**
```http
GET /api/jobs/options/search?query=php&types[]=skills&types[]=companies&limit=5
```

**Response:**
```json
{
  "success": true,
  "message": "Search completed successfully",
  "data": {
    "skills": [
      {
        "id": null,
        "value": "PHP",
        "label": "PHP",
        "count": 85
      }
    ],
    "companies": [
      {
        "id": null,
        "value": "PHP Company",
        "label": "PHP Company", 
        "count": 5
      }
    ]
  },
  "query": "php"
}
```

## Standard Response Format

Tất cả endpoint đều có format response chuẩn:

### Success Response
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": [...],
  "total": 50,
  "meta": {
    "generated_at": "2025-01-13T08:30:00.000Z",
    "cache_ttl": 3600,
    "version": "1.0"
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "error": "Detailed error (in debug mode only)"
}
```

## Option Object Format

Mỗi option object có format chuẩn:

```json
{
  "id": 123,           // ID của option (có thể null)
  "value": "string",   // Giá trị thực tế
  "label": "string",   // Label hiển thị
  "count": 50,         // Số lượng job có option này (có thể null)
  "sort_order": 1,     // Thứ tự sắp xếp (có thể null)
  "slug": "string",    // URL slug (có thể null)
  "metadata": {...}    // Thông tin bổ sung (có thể null)
}
```

## Caching

- Các endpoint được cache với TTL = 3600 seconds (1 hour)
- Search endpoints được cache với TTL = 1800 seconds (30 minutes)
- Cache key bao gồm parameters để đảm bảo uniqueness

## Best Practices

### 1. Sử dụng Form Data endpoint cho form initialization
```javascript
// Thay vì gọi nhiều API riêng lẻ
const formData = await fetch('/api/jobs/options/form-data');
```

### 2. Implement autocomplete với debounce
```javascript
// Cho search skills với debounce 300ms
const searchSkills = debounce((query) => {
  fetch(`/api/jobs/options/skills?search=${query}&limit=10`);
}, 300);
```

### 3. Cache response ở frontend
```javascript
// Cache response để tránh gọi lại không cần thiết
const cacheKey = 'job-filter-options';
const cachedData = localStorage.getItem(cacheKey);
if (!cachedData || isExpired(cachedData)) {
  // Fetch new data
}
```

## Frontend Integration Examples

### React Hook
```javascript
const useJobOptions = () => {
  const [options, setOptions] = useState({});
  const [loading, setLoading] = useState(false);
  
  const fetchFilterOptions = async () => {
    setLoading(true);
    try {
      const response = await fetch('/api/jobs/options/filter-options');
      const data = await response.json();
      setOptions(data.data);
    } catch (error) {
      console.error('Failed to fetch options:', error);
    } finally {
      setLoading(false);
    }
  };
  
  useEffect(() => {
    fetchFilterOptions();
  }, []);
  
  return { options, loading, refetch: fetchFilterOptions };
};
```

### Vue Composition API
```javascript
import { ref, onMounted } from 'vue';

export function useJobOptions() {
  const options = ref({});
  const loading = ref(false);
  
  const fetchOptions = async () => {
    loading.value = true;
    try {
      const response = await fetch('/api/jobs/options/filter-options');
      const data = await response.json();
      options.value = data.data;
    } catch (error) {
      console.error('Failed to fetch options:', error);
    } finally {
      loading.value = false;
    }
  };
  
  onMounted(fetchOptions);
  
  return { options, loading, fetchOptions };
}
```