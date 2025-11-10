<?php

/**
 * API Testing Configuration
 * Configuration file for testing Login and Update Profile APIs
 */

class ApiTestConfig {
    // Base URL from .env file
    public static $baseUrl = 'https://lamgame.vn';
    
    // API Endpoints
    public static $endpoints = [
        'login' => '/api/auth/login',
        'profile' => '/api/auth/profile'
    ];
    
    // Test credentials - adjust these based on your test environment
    public static $testUsers = [
        'valid' => [
            'email' => 'test@example.com',
            'password' => 'password123',
            'name' => 'Test User'
        ],
        'invalid_email' => [
            'email' => 'nonexistent@example.com',
            'password' => 'password123'
        ],
        'invalid_password' => [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]
    ];
    
    // Store authentication token
    public static $authToken = null;
    
    // Test statistics
    public static $stats = [
        'total' => 0,
        'passed' => 0,
        'failed' => 0,
        'start_time' => null
    ];
}

/**
 * HTTP Request Helper Class
 */
class ApiTestHelper {
    
    /**
     * Make HTTP request using cURL
     */
    public static function makeRequest($method, $url, $data = null, $headers = []) {
        $ch = curl_init();
        
        // Default headers
        $defaultHeaders = [
            'Accept: application/json',
            'Content-Type: application/json'
        ];
        
        // Merge with provided headers
        $allHeaders = array_merge($defaultHeaders, $headers);
        
        curl_setopt_array($ch, [
            CURLOPT_URL => ApiTestConfig::$baseUrl . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $allHeaders,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false, // For testing only
            CURLOPT_SSL_VERIFYHOST => false, // For testing only
        ]);
        
        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            throw new Exception("cURL Error: {$error}");
        }
        
        return [
            'http_code' => $httpCode,
            'body' => $response,
            'data' => json_decode($response, true)
        ];
    }
    
    /**
     * Make authenticated request
     */
    public static function makeAuthRequest($method, $url, $data = null, $token = null) {
        $token = $token ?: ApiTestConfig::$authToken;
        
        if (!$token) {
            throw new Exception("No authentication token available");
        }
        
        $headers = [
            "Authorization: Bearer {$token}"
        ];
        
        return self::makeRequest($method, $url, $data, $headers);
    }
}

/**
 * Test Output Helper Class
 */
class TestOutput {
    
    // ANSI color codes
    const COLOR_GREEN = "\033[32m";
    const COLOR_RED = "\033[31m";
    const COLOR_YELLOW = "\033[33m";
    const COLOR_BLUE = "\033[34m";
    const COLOR_CYAN = "\033[36m";
    const COLOR_RESET = "\033[0m";
    
    public static function header($text) {
        echo "\n" . self::COLOR_CYAN . str_repeat('=', 80) . self::COLOR_RESET . "\n";
        echo self::COLOR_BLUE . " 🧪 " . strtoupper($text) . self::COLOR_RESET . "\n";
        echo self::COLOR_CYAN . str_repeat('=', 80) . self::COLOR_RESET . "\n\n";
    }
    
    public static function subheader($text) {
        echo "\n" . self::COLOR_YELLOW . "📋 " . $text . self::COLOR_RESET . "\n";
        echo str_repeat('-', min(60, strlen($text) + 4)) . "\n";
    }
    
    public static function test($testName, $description = '') {
        echo "\n🔍 Testing: " . self::COLOR_BLUE . $testName . self::COLOR_RESET;
        if ($description) {
            echo "\n   " . $description;
        }
        echo "\n";
    }
    
    public static function success($message) {
        ApiTestConfig::$stats['passed']++;
        echo "   ✅ " . self::COLOR_GREEN . "PASS" . self::COLOR_RESET . " - {$message}\n";
    }
    
    public static function failure($message) {
        ApiTestConfig::$stats['failed']++;
        echo "   ❌ " . self::COLOR_RED . "FAIL" . self::COLOR_RESET . " - {$message}\n";
    }
    
    public static function warning($message) {
        echo "   ⚠️  " . self::COLOR_YELLOW . "WARN" . self::COLOR_RESET . " - {$message}\n";
    }
    
    public static function info($message) {
        echo "   ℹ️  " . $message . "\n";
    }
    
    public static function debug($title, $data) {
        echo "   📝 " . self::COLOR_CYAN . $title . ":" . self::COLOR_RESET . "\n";
        if (is_array($data) || is_object($data)) {
            echo "      " . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
        } else {
            echo "      " . $data . "\n";
        }
    }
    
    public static function summary() {
        $total = ApiTestConfig::$stats['passed'] + ApiTestConfig::$stats['failed'];
        $duration = microtime(true) - ApiTestConfig::$stats['start_time'];
        
        echo "\n" . self::COLOR_CYAN . str_repeat('=', 80) . self::COLOR_RESET . "\n";
        echo self::COLOR_BLUE . " 📊 TEST SUMMARY" . self::COLOR_RESET . "\n";
        echo self::COLOR_CYAN . str_repeat('=', 80) . self::COLOR_RESET . "\n";
        
        echo "Total Tests: " . $total . "\n";
        echo self::COLOR_GREEN . "Passed: " . ApiTestConfig::$stats['passed'] . self::COLOR_RESET . "\n";
        echo self::COLOR_RED . "Failed: " . ApiTestConfig::$stats['failed'] . self::COLOR_RESET . "\n";
        echo "Duration: " . round($duration, 2) . " seconds\n";
        
        if (ApiTestConfig::$stats['failed'] === 0) {
            echo "\n🎉 " . self::COLOR_GREEN . "ALL TESTS PASSED!" . self::COLOR_RESET . "\n";
        } else {
            echo "\n⚠️  " . self::COLOR_YELLOW . "SOME TESTS FAILED - CHECK LOGS ABOVE" . self::COLOR_RESET . "\n";
        }
        
        echo "\n" . self::COLOR_CYAN . str_repeat('=', 80) . self::COLOR_RESET . "\n\n";
    }
}

/**
 * Test Validator Class
 */
class TestValidator {
    
    /**
     * Validate HTTP status code
     */
    public static function validateStatusCode($expected, $actual, $testName) {
        ApiTestConfig::$stats['total']++;
        
        if ($expected == $actual) {
            TestOutput::success("HTTP Status Code: {$actual}");
            return true;
        } else {
            TestOutput::failure("HTTP Status Code: Expected {$expected}, got {$actual}");
            return false;
        }
    }
    
    /**
     * Validate JSON response structure
     */
    public static function validateJsonStructure($expectedFields, $actualData, $testName) {
        $isValid = true;
        
        foreach ($expectedFields as $field => $type) {
            if (!isset($actualData[$field])) {
                TestOutput::failure("Missing field: {$field}");
                $isValid = false;
                continue;
            }
            
            $actualType = gettype($actualData[$field]);
            if ($type === 'array' && is_array($actualData[$field])) {
                TestOutput::success("Field '{$field}' is array");
            } elseif ($type === 'object' && is_object($actualData[$field])) {
                TestOutput::success("Field '{$field}' is object");
            } elseif ($actualType === $type) {
                TestOutput::success("Field '{$field}' is {$type}");
            } else {
                TestOutput::failure("Field '{$field}' type mismatch: Expected {$type}, got {$actualType}");
                $isValid = false;
            }
        }
        
        return $isValid;
    }
    
    /**
     * Validate error response structure
     */
    public static function validateErrorResponse($response, $expectedStatus = 'error') {
        $isValid = true;
        
        if (!isset($response['status']) || $response['status'] !== $expectedStatus) {
            TestOutput::failure("Error response should have status: {$expectedStatus}");
            $isValid = false;
        } else {
            TestOutput::success("Error status is correct: {$expectedStatus}");
        }
        
        if (!isset($response['message']) || empty($response['message'])) {
            TestOutput::failure("Error response should have a message");
            $isValid = false;
        } else {
            TestOutput::success("Error message present: " . $response['message']);
        }
        
        return $isValid;
    }
    
    /**
     * Validate success response structure
     */
    public static function validateSuccessResponse($response, $expectedStatus = 'success') {
        $isValid = true;
        
        if (!isset($response['status']) || $response['status'] !== $expectedStatus) {
            TestOutput::failure("Success response should have status: {$expectedStatus}");
            $isValid = false;
        } else {
            TestOutput::success("Success status is correct: {$expectedStatus}");
        }
        
        if (!isset($response['data'])) {
            TestOutput::failure("Success response should have data field");
            $isValid = false;
        } else {
            TestOutput::success("Data field present in response");
        }
        
        return $isValid;
    }
}

/**
 * Initialize test environment
 */
function initializeTests() {
    ApiTestConfig::$stats['start_time'] = microtime(true);
    ApiTestConfig::$stats['total'] = 0;
    ApiTestConfig::$stats['passed'] = 0;
    ApiTestConfig::$stats['failed'] = 0;
    
    TestOutput::header("API Testing Suite - Login & Profile APIs");
    TestOutput::info("Base URL: " . ApiTestConfig::$baseUrl);
    TestOutput::info("Starting tests at: " . date('Y-m-d H:i:s'));
}

// Auto-load this configuration when included
if (basename($_SERVER['PHP_SELF']) !== basename(__FILE__)) {
    // Only initialize if this file is included, not run directly
}

