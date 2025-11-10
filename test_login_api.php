<?php

require_once 'test_api_config.php';

/**
 * Login API Test Suite
 * Tests all scenarios documented in docs/api/LOGIN_API.md
 */
function testLoginAPI() {
    TestOutput::subheader("Login API Tests");
    
    // Test 1: Valid Login
    testValidLogin();
    
    // Test 2: Invalid Email (non-existent)
    testInvalidEmail();
    
    // Test 3: Invalid Password
    testInvalidPassword();
    
    // Test 4: Missing Email Field
    testMissingEmailField();
    
    // Test 5: Missing Password Field
    testMissingPasswordField();
    
    // Test 6: Device Name Variations
    testDeviceNameVariations();
    
    // Test 7: Empty Request Body
    testEmptyRequestBody();
    
    // Test 8: Malformed Email
    testMalformedEmail();
}

/**
 * Test valid login credentials
 * Expected: 200 OK with token and user data
 */
function testValidLogin() {
    TestOutput::test("Valid Login", "Testing with correct email and password");
    
    try {
        $loginData = [
            'email' => ApiTestConfig::$testUsers['valid']['email'],
            'password' => ApiTestConfig::$testUsers['valid']['password'],
            'device_name' => 'API Test Suite'
        ];
        
        $response = ApiTestHelper::makeRequest('POST', ApiTestConfig::$endpoints['login'], $loginData);
        
        TestOutput::debug("Request Data", $loginData);
        TestOutput::debug("Response", $response);
        
        // Validate HTTP status code
        TestValidator::validateStatusCode(200, $response['http_code'], 'Valid Login');
        
        // Validate response structure
        if ($response['data']) {
            TestValidator::validateSuccessResponse($response['data']);
            
            // Check for required fields in success response
            $expectedFields = [
                'status' => 'string',
                'message' => 'string',
                'data' => 'array'
            ];
            TestValidator::validateJsonStructure($expectedFields, $response['data'], 'Valid Login Response Structure');
            
            // Check data structure
            if (isset($response['data']['data'])) {
                $dataFields = [
                    'access_token' => 'string',
                    'token_type' => 'string',
                    'user' => 'array'
                ];
                TestValidator::validateJsonStructure($dataFields, $response['data']['data'], 'Login Data Structure');
                
                // Store token for later tests
                if (isset($response['data']['data']['access_token'])) {
                    ApiTestConfig::$authToken = $response['data']['data']['access_token'];
                    TestOutput::success("Authentication token stored for subsequent tests");
                }
                
                // Validate user data structure
                if (isset($response['data']['data']['user'])) {
                    $userFields = [
                        'id' => 'integer',
                        'name' => 'string',
                        'email' => 'string',
                        'created_at' => 'string',
                        'updated_at' => 'string'
                    ];
                    TestValidator::validateJsonStructure($userFields, $response['data']['data']['user'], 'User Data Structure');
                }
            }
        }
        
    } catch (Exception $e) {
        TestOutput::failure("Exception occurred: " . $e->getMessage());
    }
}

/**
 * Test with non-existent email
 * Expected: 401 Unauthorized with error message
 */
function testInvalidEmail() {
    TestOutput::test("Invalid Email", "Testing with non-existent email address");
    
    try {
        $loginData = [
            'email' => ApiTestConfig::$testUsers['invalid_email']['email'],
            'password' => ApiTestConfig::$testUsers['invalid_email']['password'],
            'device_name' => 'API Test Suite'
        ];
        
        $response = ApiTestHelper::makeRequest('POST', ApiTestConfig::$endpoints['login'], $loginData);
        
        TestOutput::debug("Request Data", $loginData);
        TestOutput::debug("Response", $response);
        
        // Validate HTTP status code
        TestValidator::validateStatusCode(401, $response['http_code'], 'Invalid Email');
        
        // Validate error response structure
        if ($response['data']) {
            TestValidator::validateErrorResponse($response['data']);
            
            // Check for specific error message about user not existing
            if (isset($response['data']['message'])) {
                $message = $response['data']['message'];
                if (strpos($message, 'không tồn tại') !== false || strpos($message, 'not exist') !== false) {
                    TestOutput::success("Error message indicates user does not exist");
                } else {
                    TestOutput::warning("Error message may not be specific about non-existent user: " . $message);
                }
            }
        }
        
    } catch (Exception $e) {
        TestOutput::failure("Exception occurred: " . $e->getMessage());
    }
}

/**
 * Test with wrong password
 * Expected: 401 Unauthorized with error message
 */
function testInvalidPassword() {
    TestOutput::test("Invalid Password", "Testing with wrong password");
    
    try {
        $loginData = [
            'email' => ApiTestConfig::$testUsers['invalid_password']['email'],
            'password' => ApiTestConfig::$testUsers['invalid_password']['password'],
            'device_name' => 'API Test Suite'
        ];
        
        $response = ApiTestHelper::makeRequest('POST', ApiTestConfig::$endpoints['login'], $loginData);
        
        TestOutput::debug("Request Data", $loginData);
        TestOutput::debug("Response", $response);
        
        // Validate HTTP status code
        TestValidator::validateStatusCode(401, $response['http_code'], 'Invalid Password');
        
        // Validate error response structure
        if ($response['data']) {
            TestValidator::validateErrorResponse($response['data']);
            
            // Check for specific error about password
            if (isset($response['data']['errors']['password'])) {
                TestOutput::success("Password error field present in response");
            } else if (isset($response['data']['message'])) {
                $message = $response['data']['message'];
                if (strpos($message, 'password') !== false || strpos($message, 'mật khẩu') !== false) {
                    TestOutput::success("Error message mentions password issue");
                } else {
                    TestOutput::warning("Error message may not be specific about password: " . $message);
                }
            }
        }
        
    } catch (Exception $e) {
        TestOutput::failure("Exception occurred: " . $e->getMessage());
    }
}

/**
 * Test with missing email field
 * Expected: 422 Unprocessable Entity with validation error
 */
function testMissingEmailField() {
    TestOutput::test("Missing Email Field", "Testing request without email field");
    
    try {
        $loginData = [
            'password' => 'somepassword',
            'device_name' => 'API Test Suite'
        ];
        
        $response = ApiTestHelper::makeRequest('POST', ApiTestConfig::$endpoints['login'], $loginData);
        
        TestOutput::debug("Request Data", $loginData);
        TestOutput::debug("Response", $response);
        
        // Validate HTTP status code
        TestValidator::validateStatusCode(422, $response['http_code'], 'Missing Email Field');
        
        // Validate error response structure
        if ($response['data']) {
            TestValidator::validateErrorResponse($response['data']);
            
            // Check for email validation error
            if (isset($response['data']['errors']['email'])) {
                TestOutput::success("Email validation error present");
                if (is_array($response['data']['errors']['email'])) {
                    TestOutput::info("Email errors: " . implode(', ', $response['data']['errors']['email']));
                }
            } else {
                TestOutput::failure("Email validation error not found in response");
            }
        }
        
    } catch (Exception $e) {
        TestOutput::failure("Exception occurred: " . $e->getMessage());
    }
}

/**
 * Test with missing password field
 * Expected: 422 Unprocessable Entity with validation error
 */
function testMissingPasswordField() {
    TestOutput::test("Missing Password Field", "Testing request without password field");
    
    try {
        $loginData = [
            'email' => 'test@example.com',
            'device_name' => 'API Test Suite'
        ];
        
        $response = ApiTestHelper::makeRequest('POST', ApiTestConfig::$endpoints['login'], $loginData);
        
        TestOutput::debug("Request Data", $loginData);
        TestOutput::debug("Response", $response);
        
        // Validate HTTP status code
        TestValidator::validateStatusCode(422, $response['http_code'], 'Missing Password Field');
        
        // Validate error response structure
        if ($response['data']) {
            TestValidator::validateErrorResponse($response['data']);
            
            // Check for password validation error
            if (isset($response['data']['errors']['password'])) {
                TestOutput::success("Password validation error present");
                if (is_array($response['data']['errors']['password'])) {
                    TestOutput::info("Password errors: " . implode(', ', $response['data']['errors']['password']));
                }
            } else {
                TestOutput::failure("Password validation error not found in response");
            }
        }
        
    } catch (Exception $e) {
        TestOutput::failure("Exception occurred: " . $e->getMessage());
    }
}

/**
 * Test different device names
 * Expected: 200 OK with different device names handled correctly
 */
function testDeviceNameVariations() {
    TestOutput::test("Device Name Variations", "Testing different device name formats");
    
    $deviceNames = [
        'Web Browser',
        'Mobile App - iOS',
        'Android App',
        'Chrome Extension',
        null, // No device name
        '' // Empty device name
    ];
    
    foreach ($deviceNames as $deviceName) {
        TestOutput::info("Testing device name: " . ($deviceName ?: 'null/empty'));
        
        try {
            $loginData = [
                'email' => ApiTestConfig::$testUsers['valid']['email'],
                'password' => ApiTestConfig::$testUsers['valid']['password']
            ];
            
            if ($deviceName !== null) {
                $loginData['device_name'] = $deviceName;
            }
            
            $response = ApiTestHelper::makeRequest('POST', ApiTestConfig::$endpoints['login'], $loginData);
            
            if ($response['http_code'] == 200) {
                TestOutput::success("Device name '{$deviceName}' handled correctly");
            } else {
                TestOutput::warning("Device name '{$deviceName}' caused HTTP {$response['http_code']}");
            }
            
        } catch (Exception $e) {
            TestOutput::failure("Device name '{$deviceName}' caused exception: " . $e->getMessage());
        }
    }
}

/**
 * Test with empty request body
 * Expected: 422 Unprocessable Entity with validation errors
 */
function testEmptyRequestBody() {
    TestOutput::test("Empty Request Body", "Testing with no data sent");
    
    try {
        $response = ApiTestHelper::makeRequest('POST', ApiTestConfig::$endpoints['login'], []);
        
        TestOutput::debug("Request Data", []);
        TestOutput::debug("Response", $response);
        
        // Validate HTTP status code
        TestValidator::validateStatusCode(422, $response['http_code'], 'Empty Request Body');
        
        // Validate error response structure
        if ($response['data']) {
            TestValidator::validateErrorResponse($response['data']);
            
            // Should have both email and password errors
            if (isset($response['data']['errors']['email']) && isset($response['data']['errors']['password'])) {
                TestOutput::success("Both email and password validation errors present");
            } else {
                TestOutput::warning("Expected both email and password validation errors");
            }
        }
        
    } catch (Exception $e) {
        TestOutput::failure("Exception occurred: " . $e->getMessage());
    }
}

/**
 * Test with malformed email
 * Expected: 422 Unprocessable Entity with email validation error
 */
function testMalformedEmail() {
    TestOutput::test("Malformed Email", "Testing with invalid email format");
    
    $malformedEmails = [
        'notanemail',
        'missing@domain',
        '@missingusername.com',
        'spaces in@email.com',
        'toolong' . str_repeat('a', 250) . '@example.com'
    ];
    
    foreach ($malformedEmails as $email) {
        TestOutput::info("Testing email: {$email}");
        
        try {
            $loginData = [
                'email' => $email,
                'password' => 'somepassword',
                'device_name' => 'API Test Suite'
            ];
            
            $response = ApiTestHelper::makeRequest('POST', ApiTestConfig::$endpoints['login'], $loginData);
            
            if ($response['http_code'] == 422) {
                if (isset($response['data']['errors']['email'])) {
                    TestOutput::success("Email validation caught malformed email: {$email}");
                } else {
                    TestOutput::warning("422 status but no email error for: {$email}");
                }
            } else {
                TestOutput::warning("Malformed email '{$email}' not caught (HTTP {$response['http_code']})");
            }
            
        } catch (Exception $e) {
            TestOutput::failure("Malformed email '{$email}' caused exception: " . $e->getMessage());
        }
    }
}

// Export the main function for the test runner
if (basename($_SERVER['PHP_SELF']) !== basename(__FILE__)) {
    // This file is being included, not run directly
} else {
    // This file is being run directly for testing
    require_once 'test_api_config.php';
    initializeTests();
    testLoginAPI();
    TestOutput::summary();
}

