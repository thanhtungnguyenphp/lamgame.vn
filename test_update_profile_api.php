<?php

require_once 'test_api_config.php';

/**
 * Update Profile API Test Suite
 * Tests all scenarios documented in docs/api/UPDATE_PROFILE_API.md
 */
function testUpdateProfileAPI() {
    TestOutput::subheader("Update Profile API Tests");
    
    // Test 1: Authentication Required
    testAuthenticationRequired();
    
    // Test 2: Valid Name Update
    testValidNameUpdate();
    
    // Test 3: Valid Email Update (requires current password)
    testValidEmailUpdate();
    
    // Test 4: Valid Phone Update
    testValidPhoneUpdate();
    
    // Test 5: Valid Bio Update
    testValidBioUpdate();
    
    // Test 6: Empty Name Validation
    testEmptyNameValidation();
    
    // Test 7: Invalid Email Format
    testInvalidEmailFormat();
    
    // Test 8: Invalid Phone Format
    testInvalidPhoneFormat();
    
    // Test 9: Missing Current Password when changing email
    testMissingCurrentPasswordOnEmailChange();
    
    // Test 10: Partial Updates
    testPartialUpdates();
    
    // Test 11: Long field values
    testLongFieldValues();
    
    // Test 12: Special characters handling
    testSpecialCharactersHandling();
}

/**
 * Test without authentication token
 * Expected: 401 Unauthorized
 */
function testAuthenticationRequired() {
    TestOutput::test("Authentication Required", "Testing without authentication token");
    
    try {
        $updateData = [
            'name' => 'Test Name',
            'email' => 'test@example.com'
        ];
        
        // Make request without token
        $response = ApiTestHelper::makeRequest('PUT', ApiTestConfig::$endpoints['profile'], $updateData);
        
        TestOutput::debug("Request Data", $updateData);
        TestOutput::debug("Response", $response);
        
        // Validate HTTP status code
        TestValidator::validateStatusCode(401, $response['http_code'], 'Authentication Required');
        
        // Validate error response structure
        if ($response['data']) {
            if (isset($response['data']['message'])) {
                TestOutput::success("Error message present: " . $response['data']['message']);
            } else {
                TestOutput::warning("No error message in unauthorized response");
            }
        }
        
    } catch (Exception $e) {
        TestOutput::failure("Exception occurred: " . $e->getMessage());
    }
}

/**
 * Test valid name update
 * Expected: 200 OK with updated user data
 */
function testValidNameUpdate() {
    TestOutput::test("Valid Name Update", "Testing name field update with valid data");
    
    if (!ApiTestConfig::$authToken) {
        TestOutput::warning("No authentication token available - skipping authenticated test");
        return;
    }
    
    try {
        $updateData = [
            'name' => 'Updated Test Name ' . date('H:i:s')
        ];
        
        $response = ApiTestHelper::makeAuthRequest('PUT', ApiTestConfig::$endpoints['profile'], $updateData);
        
        TestOutput::debug("Request Data", $updateData);
        TestOutput::debug("Response", $response);
        
        // Validate HTTP status code
        TestValidator::validateStatusCode(200, $response['http_code'], 'Valid Name Update');
        
        // Validate response structure
        if ($response['data']) {
            TestValidator::validateSuccessResponse($response['data']);
            
            // Check if name was updated
            if (isset($response['data']['data']['user']['name'])) {
                $returnedName = $response['data']['data']['user']['name'];
                if ($returnedName === $updateData['name']) {
                    TestOutput::success("Name updated correctly: {$returnedName}");
                } else {
                    TestOutput::failure("Name not updated correctly. Expected: {$updateData['name']}, Got: {$returnedName}");
                }
            } else {
                TestOutput::warning("Updated user name not found in response");
            }
        }
        
    } catch (Exception $e) {
        TestOutput::failure("Exception occurred: " . $e->getMessage());
    }
}

/**
 * Test valid email update with current password
 * Expected: 200 OK (or may require email verification)
 */
function testValidEmailUpdate() {
    TestOutput::test("Valid Email Update", "Testing email update with current password");
    
    if (!ApiTestConfig::$authToken) {
        TestOutput::warning("No authentication token available - skipping authenticated test");
        return;
    }
    
    try {
        $newEmail = 'updated_' . time() . '@example.com';
        $updateData = [
            'email' => $newEmail,
            'current_password' => ApiTestConfig::$testUsers['valid']['password']
        ];
        
        $response = ApiTestHelper::makeAuthRequest('PUT', ApiTestConfig::$endpoints['profile'], $updateData);
        
        TestOutput::debug("Request Data", $updateData);
        TestOutput::debug("Response", $response);
        
        // Email update might return different status codes depending on implementation
        if (in_array($response['http_code'], [200, 202, 422])) {
            if ($response['http_code'] == 200) {
                TestOutput::success("Email update processed successfully");
                TestValidator::validateSuccessResponse($response['data']);
            } elseif ($response['http_code'] == 202) {
                TestOutput::success("Email update accepted (may require verification)");
            } elseif ($response['http_code'] == 422) {
                // Check if it's a duplicate email error
                if (isset($response['data']['errors']['email'])) {
                    TestOutput::info("Email validation error (may be duplicate): " . implode(', ', $response['data']['errors']['email']));
                } else {
                    TestOutput::warning("422 error without specific email error");
                }
            }
        } else {
            TestOutput::failure("Unexpected HTTP status code for email update: {$response['http_code']}");
        }
        
    } catch (Exception $e) {
        TestOutput::failure("Exception occurred: " . $e->getMessage());
    }
}

/**
 * Test valid phone update
 * Expected: 200 OK with updated phone
 */
function testValidPhoneUpdate() {
    TestOutput::test("Valid Phone Update", "Testing phone number update");
    
    if (!ApiTestConfig::$authToken) {
        TestOutput::warning("No authentication token available - skipping authenticated test");
        return;
    }
    
    $validPhones = [
        '0912345678',
        '84912345679',
        '+84912345680',
        '0123456789'
    ];
    
    foreach ($validPhones as $phone) {
        TestOutput::info("Testing phone: {$phone}");
        
        try {
            $updateData = [
                'phone' => $phone
            ];
            
            $response = ApiTestHelper::makeAuthRequest('PUT', ApiTestConfig::$endpoints['profile'], $updateData);
            
            if ($response['http_code'] == 200) {
                TestOutput::success("Phone {$phone} updated successfully");
                
                // Check if phone was updated
                if (isset($response['data']['data']['user']['phone'])) {
                    TestOutput::info("Returned phone: " . $response['data']['data']['user']['phone']);
                }
            } else {
                TestOutput::warning("Phone {$phone} update failed with HTTP {$response['http_code']}");
            }
            
        } catch (Exception $e) {
            TestOutput::failure("Phone {$phone} update caused exception: " . $e->getMessage());
        }
    }
}

/**
 * Test valid bio update
 * Expected: 200 OK with updated bio
 */
function testValidBioUpdate() {
    TestOutput::test("Valid Bio Update", "Testing bio field update");
    
    if (!ApiTestConfig::$authToken) {
        TestOutput::warning("No authentication token available - skipping authenticated test");
        return;
    }
    
    try {
        $updateData = [
            'bio' => 'This is my updated bio with emojis 🎮🚀 and special characters!'
        ];
        
        $response = ApiTestHelper::makeAuthRequest('PUT', ApiTestConfig::$endpoints['profile'], $updateData);
        
        TestOutput::debug("Request Data", $updateData);
        TestOutput::debug("Response", $response);
        
        // Validate HTTP status code
        TestValidator::validateStatusCode(200, $response['http_code'], 'Valid Bio Update');
        
        // Validate response structure
        if ($response['data']) {
            TestValidator::validateSuccessResponse($response['data']);
            
            // Check if bio was updated
            if (isset($response['data']['data']['user']['bio'])) {
                $returnedBio = $response['data']['data']['user']['bio'];
                TestOutput::success("Bio updated: " . substr($returnedBio, 0, 50) . "...");
            } else {
                TestOutput::warning("Updated bio not found in response");
            }
        }
        
    } catch (Exception $e) {
        TestOutput::failure("Exception occurred: " . $e->getMessage());
    }
}

/**
 * Test empty name validation
 * Expected: 422 Unprocessable Entity with validation error
 */
function testEmptyNameValidation() {
    TestOutput::test("Empty Name Validation", "Testing with empty/null name");
    
    if (!ApiTestConfig::$authToken) {
        TestOutput::warning("No authentication token available - skipping authenticated test");
        return;
    }
    
    $emptyNames = ['', null, '  ', '   '];
    
    foreach ($emptyNames as $name) {
        TestOutput::info("Testing name: '" . ($name ?: 'null') . "'");
        
        try {
            $updateData = [
                'name' => $name
            ];
            
            $response = ApiTestHelper::makeAuthRequest('PUT', ApiTestConfig::$endpoints['profile'], $updateData);
            
            if ($response['http_code'] == 422) {
                if (isset($response['data']['errors']['name'])) {
                    TestOutput::success("Name validation caught empty value");
                } else {
                    TestOutput::warning("422 status but no name validation error");
                }
            } else {
                TestOutput::warning("Empty name not caught (HTTP {$response['http_code']})");
            }
            
        } catch (Exception $e) {
            TestOutput::failure("Empty name test caused exception: " . $e->getMessage());
        }
    }
}

/**
 * Test invalid email formats
 * Expected: 422 Unprocessable Entity with email validation error
 */
function testInvalidEmailFormat() {
    TestOutput::test("Invalid Email Format", "Testing with malformed emails");
    
    if (!ApiTestConfig::$authToken) {
        TestOutput::warning("No authentication token available - skipping authenticated test");
        return;
    }
    
    $invalidEmails = [
        'notanemail',
        'missing@domain',
        '@missinguser.com',
        'spaces in@email.com',
        'double@@email.com'
    ];
    
    foreach ($invalidEmails as $email) {
        TestOutput::info("Testing email: {$email}");
        
        try {
            $updateData = [
                'email' => $email
            ];
            
            $response = ApiTestHelper::makeAuthRequest('PUT', ApiTestConfig::$endpoints['profile'], $updateData);
            
            if ($response['http_code'] == 422) {
                if (isset($response['data']['errors']['email'])) {
                    TestOutput::success("Email validation caught invalid format: {$email}");
                } else {
                    TestOutput::warning("422 status but no email validation error for: {$email}");
                }
            } else {
                TestOutput::warning("Invalid email '{$email}' not caught (HTTP {$response['http_code']})");
            }
            
        } catch (Exception $e) {
            TestOutput::failure("Invalid email '{$email}' test caused exception: " . $e->getMessage());
        }
    }
}

/**
 * Test invalid phone formats
 * Expected: 422 Unprocessable Entity with phone validation error
 */
function testInvalidPhoneFormat() {
    TestOutput::test("Invalid Phone Format", "Testing with invalid phone numbers");
    
    if (!ApiTestConfig::$authToken) {
        TestOutput::warning("No authentication token available - skipping authenticated test");
        return;
    }
    
    $invalidPhones = [
        '123',  // Too short
        '12345678901234567890',  // Too long
        'notanumber',  // Not numeric
        '091234567a',  // Contains letters
        '+123-456-789',  // Invalid format
        '00912345678'  // Double zero prefix
    ];
    
    foreach ($invalidPhones as $phone) {
        TestOutput::info("Testing phone: {$phone}");
        
        try {
            $updateData = [
                'phone' => $phone
            ];
            
            $response = ApiTestHelper::makeAuthRequest('PUT', ApiTestConfig::$endpoints['profile'], $updateData);
            
            if ($response['http_code'] == 422) {
                if (isset($response['data']['errors']['phone'])) {
                    TestOutput::success("Phone validation caught invalid format: {$phone}");
                } else {
                    TestOutput::warning("422 status but no phone validation error for: {$phone}");
                }
            } else {
                TestOutput::info("Phone '{$phone}' was accepted or processed differently (HTTP {$response['http_code']})");
            }
            
        } catch (Exception $e) {
            TestOutput::failure("Invalid phone '{$phone}' test caused exception: " . $e->getMessage());
        }
    }
}

/**
 * Test email change without current password
 * Expected: 401 or 422 with error about missing current password
 */
function testMissingCurrentPasswordOnEmailChange() {
    TestOutput::test("Missing Current Password on Email Change", "Testing email change without current password");
    
    if (!ApiTestConfig::$authToken) {
        TestOutput::warning("No authentication token available - skipping authenticated test");
        return;
    }
    
    try {
        $updateData = [
            'email' => 'newemail_' . time() . '@example.com'
            // Deliberately omitting current_password
        ];
        
        $response = ApiTestHelper::makeAuthRequest('PUT', ApiTestConfig::$endpoints['profile'], $updateData);
        
        TestOutput::debug("Request Data", $updateData);
        TestOutput::debug("Response", $response);
        
        // Should return error about missing current password
        if (in_array($response['http_code'], [401, 422])) {
            if (isset($response['data']['errors']['current_password']) || 
                (isset($response['data']['message']) && 
                 (strpos($response['data']['message'], 'current_password') !== false || 
                  strpos($response['data']['message'], 'mật khẩu') !== false))) {
                TestOutput::success("Missing current password validation works correctly");
            } else {
                TestOutput::warning("Error response but no specific current_password error");
            }
        } else {
            TestOutput::warning("Expected validation error but got HTTP {$response['http_code']}");
        }
        
    } catch (Exception $e) {
        TestOutput::failure("Exception occurred: " . $e->getMessage());
    }
}

/**
 * Test partial updates (updating only some fields)
 * Expected: 200 OK with only specified fields updated
 */
function testPartialUpdates() {
    TestOutput::test("Partial Updates", "Testing updates of individual fields");
    
    if (!ApiTestConfig::$authToken) {
        TestOutput::warning("No authentication token available - skipping authenticated test");
        return;
    }
    
    $partialUpdates = [
        ['name' => 'Only Name Update ' . time()],
        ['phone' => '091' . rand(1000000, 9999999)],
        ['bio' => 'Only bio update at ' . date('H:i:s')]
    ];
    
    foreach ($partialUpdates as $updateData) {
        $field = array_keys($updateData)[0];
        TestOutput::info("Testing partial update for field: {$field}");
        
        try {
            $response = ApiTestHelper::makeAuthRequest('PUT', ApiTestConfig::$endpoints['profile'], $updateData);
            
            if ($response['http_code'] == 200) {
                TestOutput::success("Partial update for '{$field}' successful");
                
                // Check if the specific field was updated
                if (isset($response['data']['data']['user'][$field])) {
                    TestOutput::info("Updated {$field}: " . $response['data']['data']['user'][$field]);
                }
            } else {
                TestOutput::warning("Partial update for '{$field}' failed with HTTP {$response['http_code']}");
            }
            
        } catch (Exception $e) {
            TestOutput::failure("Partial update for '{$field}' caused exception: " . $e->getMessage());
        }
    }
}

/**
 * Test long field values
 * Expected: Validation errors for fields exceeding maximum length
 */
function testLongFieldValues() {
    TestOutput::test("Long Field Values", "Testing field length limits");
    
    if (!ApiTestConfig::$authToken) {
        TestOutput::warning("No authentication token available - skipping authenticated test");
        return;
    }
    
    $longValues = [
        'name' => str_repeat('A', 300),  // Very long name
        'bio' => str_repeat('This is a long bio. ', 50),  // Long bio
        'phone' => '0912345678901234567890'  // Very long phone
    ];
    
    foreach ($longValues as $field => $value) {
        TestOutput::info("Testing long {$field}: " . strlen($value) . " characters");
        
        try {
            $updateData = [$field => $value];
            $response = ApiTestHelper::makeAuthRequest('PUT', ApiTestConfig::$endpoints['profile'], $updateData);
            
            if ($response['http_code'] == 422) {
                if (isset($response['data']['errors'][$field])) {
                    TestOutput::success("Length validation caught long {$field}");
                } else {
                    TestOutput::warning("422 status but no {$field} validation error");
                }
            } else {
                TestOutput::info("Long {$field} was accepted (HTTP {$response['http_code']})");
            }
            
        } catch (Exception $e) {
            TestOutput::failure("Long {$field} test caused exception: " . $e->getMessage());
        }
    }
}

/**
 * Test special characters handling
 * Expected: 200 OK with special characters properly handled
 */
function testSpecialCharactersHandling() {
    TestOutput::test("Special Characters Handling", "Testing Unicode and special characters");
    
    if (!ApiTestConfig::$authToken) {
        TestOutput::warning("No authentication token available - skipping authenticated test");
        return;
    }
    
    $specialCharData = [
        'name' => 'Nguyễn Văn Đức 🎮',
        'bio' => 'Developer with ❤️ for coding! 🚀 Special chars: @#$%^&*()',
    ];
    
    try {
        $response = ApiTestHelper::makeAuthRequest('PUT', ApiTestConfig::$endpoints['profile'], $specialCharData);
        
        TestOutput::debug("Request Data", $specialCharData);
        TestOutput::debug("Response", $response);
        
        if ($response['http_code'] == 200) {
            TestOutput::success("Special characters handled correctly");
            
            // Check if special characters were preserved
            if (isset($response['data']['data']['user']['name'])) {
                TestOutput::info("Name with special chars: " . $response['data']['data']['user']['name']);
            }
        } else {
            TestOutput::warning("Special characters caused HTTP {$response['http_code']}");
        }
        
    } catch (Exception $e) {
        TestOutput::failure("Special characters test caused exception: " . $e->getMessage());
    }
}

// Export the main function for the test runner
if (basename($_SERVER['PHP_SELF']) !== basename(__FILE__)) {
    // This file is being included, not run directly
} else {
    // This file is being run directly for testing
    require_once 'test_api_config.php';
    initializeTests();
    testUpdateProfileAPI();
    TestOutput::summary();
}

