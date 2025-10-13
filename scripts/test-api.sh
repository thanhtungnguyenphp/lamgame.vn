#!/bin/bash

# Job Options API Testing Script
# Usage: ./scripts/test-api.sh [base_url]

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Default base URL
BASE_URL=${1:-"https://lamgame.localhost"}
API_BASE="$BASE_URL/api/jobs/options"

# Test counters
TOTAL_TESTS=0
PASSED_TESTS=0
FAILED_TESTS=0

echo -e "${BLUE}🚀 Job Options API Testing Script${NC}"
echo -e "${BLUE}Testing API at: $BASE_URL${NC}"
echo "=================================="

# Function to test API endpoint
test_endpoint() {
    local endpoint="$1"
    local expected_status="${2:-200}"
    local description="$3"
    
    TOTAL_TESTS=$((TOTAL_TESTS + 1))
    
    echo -n "Testing: $description ... "
    
    response=$(curl -s -w "HTTPSTATUS:%{http_code};TIME:%{time_total}" \
        -H "Accept: application/json" \
        -H "Content-Type: application/json" \
        "$API_BASE$endpoint")
    
    http_code=$(echo $response | grep -o 'HTTPSTATUS:[0-9]*' | cut -d: -f2)
    time_total=$(echo $response | grep -o 'TIME:[0-9.]*' | cut -d: -f2)
    body=$(echo $response | sed -E 's/HTTPSTATUS:[0-9]*;TIME:[0-9.]*$//')
    
    if [ "$http_code" -eq "$expected_status" ]; then
        PASSED_TESTS=$((PASSED_TESTS + 1))
        echo -e "${GREEN}✅ PASS${NC} (${http_code}, ${time_total}s)"
        
        # Check if response has expected structure
        if echo "$body" | jq -e '.success' > /dev/null 2>&1; then
            success_value=$(echo "$body" | jq -r '.success')
            if [ "$success_value" = "true" ]; then
                echo "   ↳ Response structure: ✓"
            else
                echo -e "   ↳ Response structure: ${YELLOW}⚠️  success=false${NC}"
            fi
        else
            echo -e "   ↳ Response structure: ${YELLOW}⚠️  Invalid JSON${NC}"
        fi
    else
        FAILED_TESTS=$((FAILED_TESTS + 1))
        echo -e "${RED}❌ FAIL${NC} (Expected: $expected_status, Got: $http_code)"
        if [ ${#body} -lt 500 ]; then
            echo "   ↳ Response: $body"
        else
            echo "   ↳ Response too long to display"
        fi
    fi
}

# Function to test with parameters
test_endpoint_with_params() {
    local endpoint="$1"
    local params="$2"
    local expected_status="${3:-200}"
    local description="$4"
    
    test_endpoint "$endpoint?$params" "$expected_status" "$description"
}

echo -e "\n${YELLOW}📋 Basic Endpoints Testing${NC}"
echo "----------------------------"

# Test basic endpoints
test_endpoint "/filter-options" 200 "Get All Filter Options"
test_endpoint "/form-data" 200 "Get Job Form Data" 
test_endpoint "/locations" 200 "Get Locations (default)"
test_endpoint "/skills" 200 "Get Skills (default)"
test_endpoint "/companies" 200 "Get Companies (default)"
test_endpoint "/benefits" 200 "Get Benefits (default)"
test_endpoint "/salary-ranges" 200 "Get Salary Ranges"
test_endpoint "/industries" 200 "Get Industries"
test_endpoint "/popular-keywords" 200 "Get Popular Keywords"

echo -e "\n${YELLOW}🔍 Search & Filter Testing${NC}"
echo "-----------------------------"

# Test search functionality
test_endpoint_with_params "/locations" "search=HCM&limit=5" 200 "Search Locations (HCM)"
test_endpoint_with_params "/skills" "search=php&limit=10" 200 "Search Skills (PHP)"
test_endpoint_with_params "/companies" "search=FPT&limit=5" 200 "Search Companies (FPT)"
test_endpoint_with_params "/benefits" "search=bảo hiểm&limit=3" 200 "Search Benefits (Insurance)"

# Test category filtering
test_endpoint_with_params "/skills" "category=IT&limit=15" 200 "Filter Skills (IT Category)"
test_endpoint_with_params "/skills" "category=Marketing&limit=10" 200 "Filter Skills (Marketing Category)"

# Test multi-search
test_endpoint_with_params "/search" "query=php&types[]=skills&types[]=companies&limit=5" 200 "Multi-Search (PHP)"

echo -e "\n${YELLOW}⚡ Limit & Edge Cases Testing${NC}"
echo "--------------------------------"

# Test limits
test_endpoint_with_params "/locations" "limit=100" 200 "Maximum Limit (100)"
test_endpoint_with_params "/locations" "limit=200" 200 "Over Limit (should cap at 100)"

# Test edge cases
test_endpoint_with_params "/search" "query=a" 422 "Search Too Short (should fail)"
test_endpoint_with_params "/search" "query=nonexistentterm123&types[]=skills" 200 "Search Non-existent Term"
test_endpoint_with_params "/skills" "category=InvalidCategory" 200 "Invalid Category Filter"

echo -e "\n${YELLOW}🔒 Error Cases Testing${NC}"
echo "-------------------------"

# Test missing required parameters
test_endpoint_with_params "/search" "" 422 "Missing Query Parameter"
test_endpoint_with_params "/search" "query=test&types[]=invalid" 422 "Invalid Search Type"

echo -e "\n${YELLOW}⏱️  Performance Testing${NC}"
echo "-------------------------"

# Test response times (should be under 5 seconds)
start_time=$(date +%s.%N)
test_endpoint "/filter-options" 200 "Performance - Filter Options"
end_time=$(date +%s.%N)
duration=$(echo "$end_time - $start_time" | bc)
if (( $(echo "$duration < 5.0" | bc -l) )); then
    echo -e "   ↳ Performance: ${GREEN}✓ ${duration}s (< 5.0s)${NC}"
else
    echo -e "   ↳ Performance: ${RED}⚠️  ${duration}s (≥ 5.0s)${NC}"
fi

# Test cached response (should be faster on second call)
start_time=$(date +%s.%N)
test_endpoint "/locations" 200 "Performance - Cached Locations"
end_time=$(date +%s.%N)
cached_duration=$(echo "$end_time - $start_time" | bc)
echo -e "   ↳ Cached Performance: ${BLUE}ℹ️  ${cached_duration}s${NC}"

echo -e "\n${YELLOW}📊 API Health Summary${NC}"
echo "======================"

# Test original endpoints (should still work)
echo "Testing original endpoints compatibility..."

original_categories=$(curl -s -w "%{http_code}" -o /dev/null "$BASE_URL/api/jobs/categories")
original_attributes=$(curl -s -w "%{http_code}" -o /dev/null "$BASE_URL/api/jobs/attributes")

if [ "$original_categories" -eq 200 ]; then
    echo -e "Original Categories Endpoint: ${GREEN}✓ Working${NC}"
else
    echo -e "Original Categories Endpoint: ${RED}✗ Broken ($original_categories)${NC}"
fi

if [ "$original_attributes" -eq 200 ]; then
    echo -e "Original Attributes Endpoint: ${GREEN}✓ Working${NC}"
else
    echo -e "Original Attributes Endpoint: ${RED}✗ Broken ($original_attributes)${NC}"
fi

echo -e "\n${BLUE}📈 Test Results Summary${NC}"
echo "======================="
echo -e "Total Tests: $TOTAL_TESTS"
echo -e "${GREEN}Passed: $PASSED_TESTS${NC}"
echo -e "${RED}Failed: $FAILED_TESTS${NC}"

if [ $FAILED_TESTS -eq 0 ]; then
    echo -e "\n${GREEN}🎉 All tests passed! API is working correctly.${NC}"
    exit 0
else
    echo -e "\n${RED}❌ Some tests failed. Please check the API implementation.${NC}"
    exit 1
fi

# Additional health checks
echo -e "\n${YELLOW}🔍 Additional Checks${NC}"
echo "===================="

# Check if jq is available for JSON parsing
if ! command -v jq &> /dev/null; then
    echo -e "${YELLOW}⚠️  jq is not installed. Install it for better JSON validation:${NC}"
    echo "   macOS: brew install jq"
    echo "   Ubuntu: apt-get install jq"
fi

# Check if bc is available for calculations
if ! command -v bc &> /dev/null; then
    echo -e "${YELLOW}⚠️  bc is not installed. Install it for performance calculations:${NC}"
    echo "   macOS: brew install bc"
    echo "   Ubuntu: apt-get install bc"
fi

echo -e "\n${BLUE}💡 Next Steps${NC}"
echo "============="
echo "1. Import Postman collection: docs/postman/Job_Options_API.postman_collection.json"
echo "2. Run full test suite: newman run docs/postman/Job_Options_API.postman_collection.json"
echo "3. Performance testing: artillery run artillery-test.yml"
echo "4. Monitor logs: tail -f storage/logs/laravel.log"