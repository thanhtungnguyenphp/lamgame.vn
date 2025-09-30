<?php

/**
 * Test script for User Registration API
 * 
 * Usage: php test_register_api.php
 */

$baseUrl = 'https://lamgame.localhost/api/auth';
$endpoint = $baseUrl . '/register';

echo "🧪 Testing User Registration API\n";
echo "================================\n\n";

// Test cases
$testCases = [
    [
        'name' => '✅ Valid Registration',
        'data' => [
            'name' => 'Nguyễn Văn Test',
            'email' => 'test' . time() . '@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'phone' => '0909123456',
            'device_name' => 'iPhone Test',
            'terms_accepted' => 1
        ]
    ],
    [
        'name' => '❌ Missing Required Fields',
        'data' => [
            'name' => 'Test User'
            // Missing email, password, terms_accepted
        ]
    ],
    [
        'name' => '❌ Invalid Email Format',
        'data' => [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms_accepted' => 1
        ]
    ],
    [
        'name' => '❌ Password Mismatch',
        'data' => [
            'name' => 'Test User',
            'email' => 'mismatch' . time() . '@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'DifferentPassword123!',
            'terms_accepted' => 1
        ]
    ],
    [
        'name' => '❌ Terms Not Accepted',
        'data' => [
            'name' => 'Test User',
            'email' => 'terms' . time() . '@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms_accepted' => 0
        ]
    ],
    [
        'name' => '❌ Duplicate Email',
        'data' => [
            'name' => 'Test User 2',
            'email' => 'duplicate@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms_accepted' => 1
        ]
    ]
];

function makeRequest($url, $data) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return [
            'success' => false,
            'error' => $error,
            'http_code' => 0
        ];
    }
    
    return [
        'success' => true,
        'data' => json_decode($response, true),
        'http_code' => $httpCode,
        'raw_response' => $response
    ];
}

// Run tests
foreach ($testCases as $index => $testCase) {
    echo "Test " . ($index + 1) . ": " . $testCase['name'] . "\n";
    echo str_repeat('-', 50) . "\n";
    
    $result = makeRequest($endpoint, $testCase['data']);
    
    if (!$result['success']) {
        echo "❌ cURL Error: " . $result['error'] . "\n\n";
        continue;
    }
    
    $httpCode = $result['http_code'];
    $response = $result['data'];
    
    echo "HTTP Status: $httpCode\n";
    
    if (isset($response['status'])) {
        echo "API Status: " . $response['status'] . "\n";
        echo "Message: " . $response['message'] . "\n";
        
        if ($response['status'] === 'success' && isset($response['data'])) {
            echo "✅ Registration successful!\n";
            echo "User ID: " . $response['data']['user']['id'] . "\n";
            echo "User Name: " . $response['data']['user']['name'] . "\n";
            echo "Email: " . $response['data']['user']['email'] . "\n";
            echo "Token Type: " . $response['data']['token_type'] . "\n";
            echo "Profile Completed: " . ($response['data']['user']['profile_completed'] ? 'Yes' : 'No') . "\n";
        } elseif ($response['status'] === 'error' && isset($response['errors'])) {
            echo "❌ Registration failed!\n";
            echo "Errors:\n";
            foreach ($response['errors'] as $field => $errors) {
                echo "  - $field: " . implode(', ', $errors) . "\n";
            }
        }
    } else {
        echo "❌ Unexpected response format:\n";
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    }
    
    echo "\n" . str_repeat('=', 50) . "\n\n";
    
    // Small delay between requests
    sleep(1);
}

echo "🏁 Registration API Test Complete!\n\n";

// Test với duplicate email cho test case cuối
echo "🔄 Testing duplicate email registration...\n";
$duplicateTest = [
    'name' => 'Another User',
    'email' => 'duplicate@example.com',
    'password' => 'Password123!',
    'password_confirmation' => 'Password123!',
    'terms_accepted' => 1
];

$result = makeRequest($endpoint, $duplicateTest);
if ($result['success'] && isset($result['data']['status'])) {
    if ($result['data']['status'] === 'error') {
        echo "✅ Duplicate email validation working correctly!\n";
        echo "Error: " . $result['data']['message'] . "\n";
    } else {
        echo "❌ Duplicate email validation not working properly!\n";
    }
}

echo "\n🎉 All tests completed!\n";