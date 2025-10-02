<?php

/**
 * Test script for User Job Management API
 * Test all CRUD operations with authentication
 */

$baseUrl = 'http://localhost/api';
$testUser = [
    'name' => 'Test User',
    'email' => 'testuser@lamgame.vn',
    'password' => 'password123',
    'password_confirmation' => 'password123'
];

function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    if ($headers) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        throw new Exception("cURL Error: $error");
    }
    
    return [
        'status' => $httpCode,
        'body' => json_decode($response, true),
        'raw' => $response
    ];
}

function testEndpoint($description, $url, $method = 'GET', $data = null, $headers = [], $expectedStatus = 200) {
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "TEST: $description\n";
    echo str_repeat("=", 60) . "\n";
    
    try {
        $result = makeRequest($url, $method, $data, $headers);
        
        echo "URL: $method $url\n";
        if ($data) {
            echo "Request Data: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
        }
        echo "Status: {$result['status']}\n";
        echo "Expected: $expectedStatus\n";
        
        if ($result['status'] === $expectedStatus) {
            echo "✅ SUCCESS\n";
        } else {
            echo "❌ FAILED\n";
        }
        
        echo "Response:\n";
        if ($result['body']) {
            echo json_encode($result['body'], JSON_PRETTY_PRINT) . "\n";
        } else {
            echo $result['raw'] . "\n";
        }
        
        return $result;
        
    } catch (Exception $e) {
        echo "❌ ERROR: " . $e->getMessage() . "\n";
        return null;
    }
}

echo "🚀 Starting User Job Management API Tests\n";
echo "Base URL: $baseUrl\n";

// Step 1: Register test user (or login if exists)
$registerResponse = testEndpoint(
    "Register Test User",
    "$baseUrl/register",
    'POST',
    $testUser,
    ['Content-Type: application/json'],
    201
);

$token = null;
if ($registerResponse && isset($registerResponse['body']['token'])) {
    $token = $registerResponse['body']['token'];
    echo "✅ Got authentication token\n";
} else {
    // Try to login instead
    echo "Registration failed, trying to login...\n";
    $loginResponse = testEndpoint(
        "Login Test User", 
        "$baseUrl/login",
        'POST',
        [
            'email' => $testUser['email'],
            'password' => $testUser['password']
        ],
        ['Content-Type: application/json']
    );
    
    if ($loginResponse && isset($loginResponse['body']['token'])) {
        $token = $loginResponse['body']['token'];
        echo "✅ Got authentication token from login\n";
    } else {
        echo "❌ Failed to get authentication token. Exiting.\n";
        exit(1);
    }
}

$authHeaders = [
    'Authorization: Bearer ' . $token,
    'Content-Type: application/json'
];

// Step 2: Test getting empty job list
testEndpoint(
    "Get Empty Job List",
    "$baseUrl/user/jobs",
    'GET',
    null,
    $authHeaders
);

// Step 3: Test creating a new job
$newJob = [
    'title' => 'Senior PHP Developer - Test Job',
    'company_name' => 'TechCorp Vietnam',
    'description' => 'We are looking for an experienced PHP developer to join our growing team. The ideal candidate will have strong experience with Laravel framework and modern development practices. This is a great opportunity to work on exciting projects.',
    'short_description' => 'Senior developer position with Laravel expertise required',
    'job_type' => 'full-time',
    'experience_level' => 'senior',
    'salary_range' => '20-30 triệu VND',
    'job_location' => 'Ho Chi Minh City',
    'company_size' => '51-200',
    'required_skills' => ['php', 'laravel', 'mysql', 'git'],
    'education_level' => 'bachelor',
    'english_level' => 'intermediate',
    'job_benefits' => ['health-insurance', '13th-salary', 'flexible-hours'],
    'application_deadline' => date('Y-m-d', strtotime('+30 days')),
    'contact_email' => 'hr@techcorp.com',
    'contact_phone' => '0901234567',
    'company_website' => 'https://techcorp.com',
    'is_urgent' => false,
    'is_featured' => false,
    'status' => true
];

$createResponse = testEndpoint(
    "Create New Job",
    "$baseUrl/user/jobs",
    'POST',
    $newJob,
    $authHeaders,
    201
);

$jobId = null;
if ($createResponse && isset($createResponse['body']['data']['id'])) {
    $jobId = $createResponse['body']['data']['id'];
    echo "✅ Created job with ID: $jobId\n";
} else {
    echo "❌ Failed to create job\n";
}

if ($jobId) {
    // Step 4: Test getting job list (should have 1 job now)
    testEndpoint(
        "Get Job List (After Create)",
        "$baseUrl/user/jobs",
        'GET',
        null,
        $authHeaders
    );

    // Step 5: Test getting specific job details
    testEndpoint(
        "Get Job Details",
        "$baseUrl/user/jobs/$jobId",
        'GET',
        null,
        $authHeaders
    );

    // Step 6: Test updating job
    $updateData = [
        'title' => 'Senior PHP Developer - Updated Title',
        'salary_range' => '25-35 triệu VND',
        'is_urgent' => true
    ];

    testEndpoint(
        "Update Job",
        "$baseUrl/user/jobs/$jobId",
        'PUT',
        $updateData,
        $authHeaders
    );

    // Step 7: Test toggle job status
    testEndpoint(
        "Toggle Job Status",
        "$baseUrl/user/jobs/$jobId/toggle-status",
        'PATCH',
        null,
        $authHeaders
    );

    // Step 8: Test search functionality
    testEndpoint(
        "Search Jobs",
        "$baseUrl/user/jobs?search=PHP&status=active",
        'GET',
        null,
        $authHeaders
    );

    // Step 9: Test pagination
    testEndpoint(
        "Test Pagination",
        "$baseUrl/user/jobs?per_page=5&page=1",
        'GET',
        null,
        $authHeaders
    );

    // Step 10: Test invalid job ID (should return 404)
    testEndpoint(
        "Get Non-existent Job (Should Fail)",
        "$baseUrl/user/jobs/99999",
        'GET',
        null,
        $authHeaders,
        404
    );

    // Step 11: Test delete job
    testEndpoint(
        "Delete Job",
        "$baseUrl/user/jobs/$jobId",
        'DELETE',
        null,
        $authHeaders
    );

    // Step 12: Verify job is deleted
    testEndpoint(
        "Verify Job Deleted (Should Fail)",
        "$baseUrl/user/jobs/$jobId",
        'GET',
        null,
        $authHeaders,
        404
    );
}

// Step 13: Test validation errors
echo "\n" . str_repeat("=", 60) . "\n";
echo "Testing Validation Errors\n";
echo str_repeat("=", 60) . "\n";

$invalidJob = [
    'title' => '', // Empty title should fail
    'company_name' => '', // Empty company name should fail
    'description' => 'Too short', // Too short description should fail
    'job_type' => 'invalid-type', // Invalid job type should fail
    'contact_email' => 'invalid-email' // Invalid email should fail
];

testEndpoint(
    "Create Job with Validation Errors (Should Fail)",
    "$baseUrl/user/jobs",
    'POST',
    $invalidJob,
    $authHeaders,
    422
);

// Step 14: Test without authentication (should fail)
testEndpoint(
    "Access Without Auth (Should Fail)",
    "$baseUrl/user/jobs",
    'GET',
    null,
    ['Content-Type: application/json'],
    401
);

echo "\n" . str_repeat("=", 80) . "\n";
echo "🏁 All tests completed!\n";
echo str_repeat("=", 80) . "\n";

?>