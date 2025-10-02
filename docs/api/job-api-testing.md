# Job API Testing Guide

Hướng dẫn này cung cấp các cách test toàn diện cho các Job API endpoints của LamGame.vn.

## Chuẩn bị môi trường test

### 1. Environment Setup

#### Development Environment
```bash
BASE_URL="https://lamgame.localhost/api"
```

#### Production Environment  
```bash
BASE_URL="https://lamgame.vn/api"
```

### 2. Authentication Setup

Để test User Job Management API, bạn cần lấy Sanctum token:

```bash
# Login để lấy token
curl -X POST "${BASE_URL}/login" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "your-email@example.com",
    "password": "your-password"
  }'

# Lưu token vào biến
export TOKEN="your-sanctum-token-here"
```

### 3. Test Data Setup

```bash
# Tạo admin user (nếu chưa có)
docker-compose exec php php artisan tinker --execute="
use Webkul\User\Models\Admin; 
Admin::create([
  'name' => 'Test Admin',
  'email' => 'admin@test.com', 
  'password' => bcrypt('password123')
]);
"

# Tạo token cho admin user
docker-compose exec php php artisan tinker --execute="
use Webkul\User\Models\Admin;
\$admin = Admin::where('email', 'admin@test.com')->first();
\$token = \$admin->createToken('test-token');
echo \$token->plainTextToken;
"
```

---

## Testing Public Job API

### Test Suite 1: Danh sách jobs công khai

#### Test Case 1.1: Lấy danh sách cơ bản
```bash
curl -X GET "${BASE_URL}/jobs" \
  -H "Accept: application/json" \
  -w "\nHTTP Status: %{http_code}\nResponse Time: %{time_total}s\n"
```

**Expected:**
- Status: 200
- Response có `success: true`
- Data là array
- Có pagination object

#### Test Case 1.2: Tìm kiếm với keyword
```bash
curl -X GET "${BASE_URL}/jobs?search=developer" \
  -H "Accept: application/json" \
  -w "\nHTTP Status: %{http_code}\n"
```

#### Test Case 1.3: Filter theo job_type
```bash
curl -X GET "${BASE_URL}/jobs?job_type=full-time" \
  -H "Accept: application/json"
```

#### Test Case 1.4: Pagination
```bash
curl -X GET "${BASE_URL}/jobs?per_page=5&page=2" \
  -H "Accept: application/json"
```

#### Test Case 1.5: Sort theo salary
```bash
curl -X GET "${BASE_URL}/jobs?sort=salary_high" \
  -H "Accept: application/json"
```

### Test Suite 2: Chi tiết job

#### Test Case 2.1: Lấy job detail hợp lệ
```bash
curl -X GET "${BASE_URL}/jobs/1" \
  -H "Accept: application/json" \
  -w "\nHTTP Status: %{http_code}\n"
```

#### Test Case 2.2: Job không tồn tại
```bash
curl -X GET "${BASE_URL}/jobs/99999" \
  -H "Accept: application/json" \
  -w "\nHTTP Status: %{http_code}\n"
```

**Expected:**
- Status: 404
- Response có error message

### Test Suite 3: Categories & Attributes

#### Test Case 3.1: Lấy categories
```bash
curl -X GET "${BASE_URL}/jobs/categories" \
  -H "Accept: application/json"
```

#### Test Case 3.2: Lấy attributes
```bash
curl -X GET "${BASE_URL}/jobs/attributes" \
  -H "Accept: application/json"
```

---

## Testing User Job Management API

### Test Suite 4: Authentication

#### Test Case 4.1: Access mà không có token
```bash
curl -X GET "${BASE_URL}/user/jobs" \
  -H "Accept: application/json" \
  -w "\nHTTP Status: %{http_code}\n"
```

**Expected:**
- Status: 401
- Error: "Unauthenticated."

#### Test Case 4.2: Token không hợp lệ
```bash
curl -X GET "${BASE_URL}/user/jobs" \
  -H "Authorization: Bearer invalid-token" \
  -H "Accept: application/json" \
  -w "\nHTTP Status: %{http_code}\n"
```

### Test Suite 5: CRUD Operations

#### Test Case 5.1: Lấy danh sách jobs của user (empty)
```bash
curl -X GET "${BASE_URL}/user/jobs" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### Test Case 5.2: Tạo job mới
```bash
curl -X POST "${BASE_URL}/user/jobs" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Job - Senior Developer",
    "company_name": "Test Company",
    "description": "This is a comprehensive test job description that meets the minimum length requirement. We are looking for an experienced developer to join our dynamic team. The ideal candidate should have strong technical skills and the ability to work in a fast-paced environment.",
    "short_description": "Test job for API testing",
    "job_type": "full-time",
    "experience_level": "senior",
    "salary_range": "20-30 triệu VND",
    "job_location": "Ho Chi Minh City",
    "contact_email": "test@example.com"
  }' \
  -w "\nHTTP Status: %{http_code}\n"
```

**Save job ID for next tests:**
```bash
JOB_ID=$(curl -s -X POST "${BASE_URL}/user/jobs" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{...}' | jq -r '.data.id')
echo "Created Job ID: $JOB_ID"
```

#### Test Case 5.3: Lấy chi tiết job vừa tạo
```bash
curl -X GET "${BASE_URL}/user/jobs/$JOB_ID" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### Test Case 5.4: Cập nhật job
```bash
curl -X PUT "${BASE_URL}/user/jobs/$JOB_ID" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "UPDATED - Test Job",
    "company_name": "Test Company UPDATED",
    "description": "This is an updated comprehensive test job description that meets the minimum length requirement. We are looking for an experienced developer to join our dynamic team with additional requirements.",
    "job_type": "full-time",
    "experience_level": "senior",
    "job_location": "Ho Chi Minh City",
    "contact_email": "test@example.com",
    "salary_range": "25-35 triệu VND",
    "is_urgent": true
  }'
```

#### Test Case 5.5: Toggle status job
```bash
curl -X PATCH "${BASE_URL}/user/jobs/$JOB_ID/toggle-status" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### Test Case 5.6: Lấy danh sách jobs (sau khi tạo)
```bash
curl -X GET "${BASE_URL}/user/jobs" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### Test Case 5.7: Xóa job
```bash
curl -X DELETE "${BASE_URL}/user/jobs/$JOB_ID" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### Test Case 5.8: Xác nhận job đã bị xóa
```bash
curl -X GET "${BASE_URL}/user/jobs/$JOB_ID" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" \
  -w "\nHTTP Status: %{http_code}\n"
```

**Expected:**
- Status: 404

### Test Suite 6: Validation Testing

#### Test Case 6.1: Tạo job với dữ liệu thiếu (required fields)
```bash
curl -X POST "${BASE_URL}/user/jobs" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "",
    "company_name": "",
    "description": "Short desc"
  }' \
  -w "\nHTTP Status: %{http_code}\n"
```

**Expected:**
- Status: 422
- Errors object với validation messages

#### Test Case 6.2: Invalid email format
```bash
curl -X POST "${BASE_URL}/user/jobs" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Job",
    "company_name": "Test Company",
    "description": "This is a long enough description that meets the minimum requirements for job posting validation rules.",
    "job_type": "full-time",
    "experience_level": "senior",
    "job_location": "Ho Chi Minh City",
    "contact_email": "invalid-email-format"
  }'
```

#### Test Case 6.3: Invalid date format
```bash
curl -X POST "${BASE_URL}/user/jobs" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Job",
    "company_name": "Test Company", 
    "description": "This is a long enough description that meets the minimum requirements for job posting validation rules.",
    "job_type": "full-time",
    "experience_level": "senior",
    "job_location": "Ho Chi Minh City",
    "contact_email": "test@example.com",
    "application_deadline": "invalid-date"
  }'
```

#### Test Case 6.4: Invalid enum values
```bash
curl -X POST "${BASE_URL}/user/jobs" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Job",
    "company_name": "Test Company",
    "description": "This is a long enough description that meets the minimum requirements for job posting validation rules.",
    "job_type": "invalid-job-type",
    "experience_level": "invalid-level",
    "job_location": "Ho Chi Minh City",
    "contact_email": "test@example.com"
  }'
```

### Test Suite 7: Authorization Testing

#### Test Case 7.1: Cố truy cập job của user khác
Tạo user thứ 2 và token:
```bash
# Tạo user thứ 2
SECOND_TOKEN="second-user-token"

# Tạo job với user 1
JOB_ID_USER1=$(curl -s -X POST "${BASE_URL}/user/jobs" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{...}' | jq -r '.data.id')

# Cố truy cập job của user 1 bằng token user 2
curl -X GET "${BASE_URL}/user/jobs/$JOB_ID_USER1" \
  -H "Authorization: Bearer $SECOND_TOKEN" \
  -H "Accept: application/json" \
  -w "\nHTTP Status: %{http_code}\n"
```

**Expected:**
- Status: 404
- Error: "Job not found or access denied"

### Test Suite 8: Search và Filter

#### Test Case 8.1: Search theo title
```bash
curl -X GET "${BASE_URL}/user/jobs?search=developer" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### Test Case 8.2: Filter theo status
```bash
curl -X GET "${BASE_URL}/user/jobs?status=active" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### Test Case 8.3: Pagination
```bash
curl -X GET "${BASE_URL}/user/jobs?per_page=5&page=1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### Test Case 8.4: Sorting
```bash
curl -X GET "${BASE_URL}/user/jobs?sort=updated_at&direction=asc" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

---

## Performance Testing

### Test Suite 9: Load Testing

#### Test Case 9.1: Rate Limiting
```bash
# Test rate limiting (60 requests/minute)
for i in {1..65}; do
  curl -s -X GET "${BASE_URL}/user/jobs" \
    -H "Authorization: Bearer $TOKEN" \
    -w "Request $i: %{http_code}\n" \
    -o /dev/null
  sleep 0.5
done
```

**Expected:** Sau 60 requests sẽ nhận được status 429

#### Test Case 9.2: Response Time
```bash
curl -X GET "${BASE_URL}/jobs" \
  -H "Accept: application/json" \
  -w "Response Time: %{time_total}s\n" \
  -o /dev/null
```

#### Test Case 9.3: Concurrent Requests
```bash
# Test 10 concurrent requests
for i in {1..10}; do
  curl -X GET "${BASE_URL}/jobs" \
    -H "Accept: application/json" \
    -w "Request $i: %{time_total}s\n" \
    -o /dev/null &
done
wait
```

---

## Automated Test Scripts

### Bash Test Script
```bash
#!/bin/bash

# File: test_job_api.sh

BASE_URL="https://lamgame.localhost/api"
TOKEN="your-token-here"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test counter
TESTS_RUN=0
TESTS_PASSED=0

# Function to run test
run_test() {
    local test_name="$1"
    local expected_status="$2"
    local curl_command="$3"
    
    echo -e "${YELLOW}Testing: $test_name${NC}"
    
    response=$(eval "$curl_command -w '\n%{http_code}'")
    actual_status=$(echo "$response" | tail -n1)
    body=$(echo "$response" | head -n -1)
    
    TESTS_RUN=$((TESTS_RUN + 1))
    
    if [ "$actual_status" -eq "$expected_status" ]; then
        echo -e "${GREEN}✅ PASS${NC} (Status: $actual_status)"
        TESTS_PASSED=$((TESTS_PASSED + 1))
    else
        echo -e "${RED}❌ FAIL${NC} (Expected: $expected_status, Got: $actual_status)"
        echo "Response: $body"
    fi
    echo ""
}

echo "🚀 Starting Job API Tests..."
echo "Base URL: $BASE_URL"
echo ""

# Test Public API
run_test "Get public jobs list" 200 "curl -s -X GET '${BASE_URL}/jobs' -H 'Accept: application/json'"

run_test "Search public jobs" 200 "curl -s -X GET '${BASE_URL}/jobs?search=developer' -H 'Accept: application/json'"

run_test "Get job categories" 200 "curl -s -X GET '${BASE_URL}/jobs/categories' -H 'Accept: application/json'"

run_test "Get job attributes" 200 "curl -s -X GET '${BASE_URL}/jobs/attributes' -H 'Accept: application/json'"

# Test User API (with authentication)
run_test "Get user jobs (authenticated)" 200 "curl -s -X GET '${BASE_URL}/user/jobs' -H 'Authorization: Bearer $TOKEN' -H 'Accept: application/json'"

run_test "Get user jobs (unauthenticated)" 401 "curl -s -X GET '${BASE_URL}/user/jobs' -H 'Accept: application/json'"

# Create job test
create_job_data='{
  "title": "Test Job Script",
  "company_name": "Test Company",
  "description": "This is a comprehensive test job description that meets the minimum length requirement for the API validation rules.",
  "job_type": "full-time",
  "experience_level": "senior", 
  "job_location": "Ho Chi Minh City",
  "contact_email": "test@example.com"
}'

run_test "Create new job" 201 "curl -s -X POST '${BASE_URL}/user/jobs' -H 'Authorization: Bearer $TOKEN' -H 'Content-Type: application/json' -d '$create_job_data'"

# Validation error test
invalid_job_data='{
  "title": "",
  "company_name": "",
  "description": "Short"
}'

run_test "Create job with validation errors" 422 "curl -s -X POST '${BASE_URL}/user/jobs' -H 'Authorization: Bearer $TOKEN' -H 'Content-Type: application/json' -d '$invalid_job_data'"

echo "🏁 Test Results:"
echo "Tests Run: $TESTS_RUN"
echo "Tests Passed: $TESTS_PASSED"
echo "Tests Failed: $((TESTS_RUN - TESTS_PASSED))"

if [ $TESTS_PASSED -eq $TESTS_RUN ]; then
    echo -e "${GREEN}All tests passed! 🎉${NC}"
    exit 0
else
    echo -e "${RED}Some tests failed! 😞${NC}"
    exit 1
fi
```

### Python Test Script
```python
#!/usr/bin/env python3

import requests
import json
import time
from typing import Dict, Any

class JobAPITester:
    def __init__(self, base_url: str, token: str = None):
        self.base_url = base_url
        self.token = token
        self.session = requests.Session()
        self.tests_run = 0
        self.tests_passed = 0
        
    def test_request(self, name: str, method: str, endpoint: str, 
                    data: Dict = None, expected_status: int = 200,
                    auth_required: bool = False):
        """Run a single API test"""
        url = f"{self.base_url}{endpoint}"
        headers = {'Accept': 'application/json'}
        
        if auth_required and self.token:
            headers['Authorization'] = f'Bearer {self.token}'
            
        if data:
            headers['Content-Type'] = 'application/json'
            
        print(f"Testing: {name}")
        
        try:
            response = self.session.request(
                method=method,
                url=url,
                json=data,
                headers=headers
            )
            
            self.tests_run += 1
            
            if response.status_code == expected_status:
                print(f"✅ PASS (Status: {response.status_code})")
                self.tests_passed += 1
                return response
            else:
                print(f"❌ FAIL (Expected: {expected_status}, Got: {response.status_code})")
                print(f"Response: {response.text[:200]}...")
                return None
                
        except Exception as e:
            print(f"❌ ERROR: {e}")
            return None
        finally:
            print()
            
    def run_tests(self):
        """Run all API tests"""
        print("🚀 Starting Job API Tests")
        print(f"Base URL: {self.base_url}")
        print()
        
        # Public API Tests
        self.test_request("Get public jobs list", "GET", "/jobs")
        self.test_request("Search public jobs", "GET", "/jobs?search=developer")
        self.test_request("Get job categories", "GET", "/jobs/categories")
        self.test_request("Get job attributes", "GET", "/jobs/attributes")
        
        # Authentication Tests
        self.test_request("Access without auth", "GET", "/user/jobs", expected_status=401)
        
        if self.token:
            self.test_request("Get user jobs", "GET", "/user/jobs", auth_required=True)
            
            # Create job test
            job_data = {
                "title": "Python Test Job",
                "company_name": "Test Company",
                "description": "This is a comprehensive test job description that meets the minimum length requirement for the API validation rules and testing purposes.",
                "job_type": "full-time",
                "experience_level": "senior",
                "job_location": "Ho Chi Minh City", 
                "contact_email": "test@example.com"
            }
            
            create_response = self.test_request(
                "Create new job", "POST", "/user/jobs",
                data=job_data, expected_status=201, auth_required=True
            )
            
            if create_response and create_response.json().get('data'):
                job_id = create_response.json()['data']['id']
                
                # Test other operations with created job
                self.test_request(f"Get job details", "GET", f"/user/jobs/{job_id}", auth_required=True)
                
                update_data = {
                    "title": "Updated Python Test Job",
                    "salary_range": "30-40 triệu VND"
                }
                self.test_request("Update job", "PUT", f"/user/jobs/{job_id}", 
                                data=update_data, auth_required=True)
                                
                self.test_request("Toggle job status", "PATCH", 
                                f"/user/jobs/{job_id}/toggle-status", auth_required=True)
                                
                self.test_request("Delete job", "DELETE", f"/user/jobs/{job_id}", 
                                auth_required=True)
            
            # Validation tests
            invalid_data = {
                "title": "",
                "company_name": "",
                "description": "Short"
            }
            
            self.test_request("Create job with validation errors", "POST", "/user/jobs",
                            data=invalid_data, expected_status=422, auth_required=True)
        
        # Results
        print("🏁 Test Results:")
        print(f"Tests Run: {self.tests_run}")
        print(f"Tests Passed: {self.tests_passed}")
        print(f"Tests Failed: {self.tests_run - self.tests_passed}")
        
        if self.tests_passed == self.tests_run:
            print("All tests passed! 🎉")
            return True
        else:
            print("Some tests failed! 😞")
            return False

if __name__ == "__main__":
    BASE_URL = "https://lamgame.localhost/api"
    TOKEN = "your-token-here"  # Replace with actual token
    
    tester = JobAPITester(BASE_URL, TOKEN)
    success = tester.run_tests()
    
    exit(0 if success else 1)
```

---

## Monitoring & Debugging

### Log Checking
```bash
# Check Laravel logs
docker-compose exec php tail -f storage/logs/laravel.log

# Check nginx access logs
docker-compose logs -f nginx

# Check PHP errors
docker-compose logs -f php
```

### Database Verification
```bash
# Check job records
docker-compose exec php php artisan tinker --execute="
use Webkul\Product\Models\Product;
echo 'Total jobs: ' . Product::whereHas('categories', function(\$q) {
  \$q->where('category_id', 102);
})->count();
"

# Check user ownership
docker-compose exec php php artisan tinker --execute="
use Webkul\Product\Models\Product;
\$jobs = Product::whereNotNull('created_by_admin_id')->get(['id', 'created_by_admin_id']);
foreach(\$jobs as \$job) {
  echo 'Job ' . \$job->id . ' owned by admin ' . \$job->created_by_admin_id . PHP_EOL;
}
"
```

### Performance Monitoring
```bash
# Monitor response times
curl -w "@curl-format.txt" -o /dev/null "${BASE_URL}/jobs"

# curl-format.txt content:
# time_namelookup:  %{time_namelookup}\n
# time_connect:     %{time_connect}\n  
# time_appconnect:  %{time_appconnect}\n
# time_pretransfer: %{time_pretransfer}\n
# time_redirect:    %{time_redirect}\n
# time_starttransfer: %{time_starttransfer}\n
# time_total:       %{time_total}\n
```

---

## Continuous Integration

### GitHub Actions Example
```yaml
name: Job API Tests

on: [push, pull_request]

jobs:
  api-tests:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup environment
      run: |
        cp .env.testing .env
        docker-compose up -d
        
    - name: Run API tests
      run: |
        chmod +x test_job_api.sh
        ./test_job_api.sh
        
    - name: Cleanup
      run: docker-compose down
```

Hướng dẫn này cung cấp framework đầy đủ để test tất cả các khía cạnh của Job API, từ basic functionality đến security, performance, và automation testing.