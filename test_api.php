<?php

/**
 * Simple API Test Script
 * Run: php test_api.php
 */

function testAPI($method, $url, $data = null, $description = '') {
    echo "\n" . str_repeat('=', 60) . "\n";
    echo "Testing: {$description}\n";
    echo "Method: {$method}\n";
    echo "URL: {$url}\n";
    
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/json',
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ]);
    
    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        echo "Data: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
    
    echo "\nResponse:\n";
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    if ($error) {
        echo "CURL Error: {$error}\n";
        return false;
    }
    
    echo "HTTP Code: {$httpCode}\n";
    echo "Body: " . ($response ? json_encode(json_decode($response), JSON_PRETTY_PRINT) : 'No response body') . "\n";
    
    return $httpCode >= 200 && $httpCode < 300;
}

// Base URL - adjust as needed
$baseUrl = 'http://localhost:8000/api/jobs';

// Sample job data
$sampleJobData = [
    'title' => 'API Test Job - Unity Developer',
    'company_name' => 'Test Company',
    'description' => '<h2>Test Job Description</h2><p>This is a test job created by API.</p>',
    'short_description' => 'Test Unity Developer position via API',
    'job_type' => 'full-time',
    'experience_level' => 'junior',
    'salary_range' => '20m-30m',
    'job_location' => 'Hồ Chí Minh',
    'company_size' => 'Nhỏ (10-50 người)',
    'required_skills' => ['Unity', 'C#'],
    'education_level' => 'Đại học',
    'english_level' => 'Cơ bản',
    'job_benefits' => ['Bảo hiểm sức khỏe', 'Đào tạo & phát triển'],
    'application_deadline' => '2025-12-31',
    'contact_email' => 'test@company.com',
    'contact_phone' => '0123456789',
    'company_website' => 'https://testcompany.com',
    'application_method' => 'email',
    'is_urgent' => false,
    'is_featured' => false,
    'categories' => [102],
    'meta_title' => 'API Test Job - Unity Developer',
    'meta_description' => 'Test job posting created via API',
    'meta_keywords' => 'test, api, unity, developer'
];

echo "🚀 Starting Job Posting API Tests...\n";

// Test 1: Get job categories
$success1 = testAPI('GET', $baseUrl . '/categories', null, 'Get Job Categories');

// Test 2: Get job attributes
$success2 = testAPI('GET', $baseUrl . '/attributes', null, 'Get Job Attributes');

// Test 3: Get existing jobs (should work even if empty)
$success3 = testAPI('GET', $baseUrl . '?per_page=5', null, 'Get Job Postings (List)');

// Test 4: Create new job
$success4 = testAPI('POST', $baseUrl, $sampleJobData, 'Create New Job Posting');

// Test 5: If creation successful, get the specific job (assuming ID 1 for demo)
if ($success4) {
    $success5 = testAPI('GET', $baseUrl . '/1', null, 'Get Specific Job Posting (ID: 1)');
    
    // Test 6: Update the job
    $updateData = [
        'title' => 'Updated API Test Job - Senior Unity Developer',
        'salary_range' => '30m-50m',
        'is_urgent' => true
    ];
    $success6 = testAPI('PUT', $baseUrl . '/1', $updateData, 'Update Job Posting (ID: 1)');
    
    // Test 7: Publish the job
    $success7 = testAPI('POST', $baseUrl . '/1/publish', null, 'Publish Job Posting (ID: 1)');
} else {
    echo "\n❌ Job creation failed, skipping related tests...\n";
}

// Test 8: Search jobs
$success8 = testAPI('GET', $baseUrl . '?search=Unity&job_type=full-time', null, 'Search Jobs (Unity + Full-time)');

echo "\n" . str_repeat('=', 60) . "\n";
echo "🏁 API Tests Completed!\n";

// Check if server is running
echo "\n📊 Quick connectivity check:\n";
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://localhost:8000',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_NOBODY => true,
    CURLOPT_TIMEOUT => 5,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
]);

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ Cannot connect to http://localhost:8000 - {$error}\n";
    echo "💡 Make sure your Laravel development server is running:\n";
    echo "   php artisan serve --host=0.0.0.0 --port=8000\n";
} else {
    echo "✅ Server is responding (HTTP {$httpCode})\n";
}

echo "\n📝 Notes:\n";
echo "- Make sure database is properly configured and running\n";
echo "- Ensure job categories exist (especially category ID 102 for 'Việc Làm')\n";
echo "- Check Laravel logs if any tests fail: storage/logs/laravel.log\n";
echo "- Adjust \$baseUrl if your server runs on different host/port\n";

?>