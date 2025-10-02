<?php

// Test User Job API with product_flat sync
$baseUrl = 'https://lamgame.localhost';
$apiUrl = $baseUrl . '/api/user/jobs';

// Mock user authentication (replace with actual token)
$authToken = 'Bearer your-auth-token-here'; // You need to get this from actual login

// Test data for creating a job
$jobData = [
    'title' => 'Senior Game Developer - Fixed Product Flat',
    'description' => 'Exciting opportunity for experienced game developer to join our team.',
    'short_description' => 'Game developer position with competitive salary.',
    'job_type' => 'Full-time',
    'experience_level' => 'Senior',
    'salary_range' => '25,000,000 - 35,000,000 VND',
    'job_location' => 'Ho Chi Minh City',
    'company_name' => 'LamGame Studios',
    'company_size' => '50-100',
    'required_skills' => ['Unity', 'C#', 'Game Design'],
    'education_level' => 'University',
    'english_level' => 'Good',
    'job_benefits' => ['Health insurance', '13th month salary', 'Performance bonus'],
    'application_deadline' => '2025-01-01',
    'contact_email' => 'hr@lamgamestudios.com',
    'contact_phone' => '0901234567',
    'company_website' => 'https://lamgamestudios.com',
    'is_urgent' => true,
    'is_featured' => false,
    'application_method' => 'email',
    'meta_title' => 'Senior Game Developer Job',
    'meta_description' => 'Join our game development team',
    'meta_keywords' => 'game developer, unity, c#',
    'categories' => [102] // Job category ID
];

function makeApiRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => array_merge([
            'Content-Type: application/json',
            'Accept: application/json'
        ], $headers),
        CURLOPT_TIMEOUT => 30
    ]);
    
    if ($method !== 'GET') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['error' => $error, 'http_code' => $httpCode];
    }
    
    return [
        'http_code' => $httpCode,
        'response' => json_decode($response, true),
        'raw_response' => $response
    ];
}

echo "=== Testing User Job API with Product Flat Sync ===\n\n";

// Test 1: Create job (without auth for now - will fail but we can see the structure)
echo "1. Testing job creation...\n";
$createResult = makeApiRequest($apiUrl, 'POST', $jobData, [
    // 'Authorization: ' . $authToken  // Uncomment when you have token
]);

echo "HTTP Code: " . $createResult['http_code'] . "\n";
echo "Response: " . json_encode($createResult['response'], JSON_PRETTY_PRINT) . "\n\n";

if ($createResult['http_code'] == 201 && isset($createResult['response']['data']['id'])) {
    $jobId = $createResult['response']['data']['id'];
    echo "✅ Job created successfully with ID: $jobId\n\n";
    
    // Test 2: Check if job exists in product table
    echo "2. Checking database consistency...\n";
    
    // You would need to add database check here
    echo "Please check the database manually:\n";
    echo "SELECT * FROM products WHERE id = $jobId;\n";
    echo "SELECT * FROM product_flat WHERE product_id = $jobId;\n";
    echo "SELECT * FROM product_attribute_values WHERE product_id = $jobId;\n";
    echo "SELECT * FROM product_categories WHERE product_id = $jobId;\n";
    echo "SELECT * FROM product_channels WHERE product_id = $jobId;\n\n";
    
} elseif ($createResult['http_code'] == 401) {
    echo "❌ Authentication required. Please:\n";
    echo "1. Login to get auth token\n";
    echo "2. Update \$authToken variable\n";
    echo "3. Uncomment Authorization header\n\n";
} else {
    echo "❌ Job creation failed\n\n";
}

// Test 3: List jobs (without auth)
echo "3. Testing job listing...\n";
$listResult = makeApiRequest($apiUrl, 'GET', null, [
    // 'Authorization: ' . $authToken  // Uncomment when you have token
]);

echo "HTTP Code: " . $listResult['http_code'] . "\n";
if ($listResult['response']) {
    echo "Response structure: " . json_encode(array_keys($listResult['response']), JSON_PRETTY_PRINT) . "\n";
} else {
    echo "Raw response: " . $listResult['raw_response'] . "\n";
}

echo "\n=== Test Complete ===\n";
echo "Next steps:\n";
echo "1. Get authentication token from login\n";
echo "2. Update this script with the token\n";
echo "3. Run the test again\n";
echo "4. Check database tables for data synchronization\n";

?>