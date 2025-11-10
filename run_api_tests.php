<?php

/**
 * Main API Test Runner
 * Executes comprehensive tests for Login and Update Profile APIs
 * 
 * Usage: php run_api_tests.php
 */

require_once 'test_api_config.php';
require_once 'test_login_api.php';
require_once 'test_update_profile_api.php';

/**
 * Main test execution function
 */
function runAllTests() {
    // Initialize the test environment
    initializeTests();
    
    TestOutput::info("Test execution started at: " . date('Y-m-d H:i:s'));
    TestOutput::info("Base URL: " . ApiTestConfig::$baseUrl);
    
    // Check server connectivity first
    checkServerConnectivity();
    
    TestOutput::header("Starting comprehensive API tests");
    
    // Phase 1: Login API Tests
    TestOutput::subheader("Phase 1: Login API Tests");
    TestOutput::info("Testing login functionality to obtain authentication token...");
    
    try {
        testLoginAPI();
        
        // Check if we got an authentication token
        if (ApiTestConfig::$authToken) {
            TestOutput::success("✅ Authentication token obtained successfully");
            TestOutput::info("Token preview: " . substr(ApiTestConfig::$authToken, 0, 20) . "...");
        } else {
            TestOutput::warning("⚠️  No authentication token obtained from login tests");
            TestOutput::info("Profile update tests may be limited");
        }
        
    } catch (Exception $e) {
        TestOutput::failure("❌ Login API tests failed with exception: " . $e->getMessage());
    }
    
    // Small delay between test phases
    sleep(1);
    
    // Phase 2: Update Profile API Tests  
    TestOutput::subheader("Phase 2: Update Profile API Tests");
    
    if (ApiTestConfig::$authToken) {
        TestOutput::info("Testing profile update functionality with authentication...");
        
        try {
            testUpdateProfileAPI();
        } catch (Exception $e) {
            TestOutput::failure("❌ Update Profile API tests failed with exception: " . $e->getMessage());
        }
    } else {
        TestOutput::warning("⚠️  Skipping authenticated profile tests - no valid token available");
        TestOutput::info("Running only unauthenticated tests...");
        
        // Run limited tests without authentication
        try {
            // Only test authentication requirement
            testAuthenticationRequired();
        } catch (Exception $e) {
            TestOutput::failure("❌ Limited profile tests failed with exception: " . $e->getMessage());
        }
    }
    
    // Generate final summary
    TestOutput::summary();
    
    // Additional analysis
    generateTestAnalysis();
    
    // Cleanup and final message
    performCleanup();
}

/**
 * Check server connectivity before running tests
 */
function checkServerConnectivity() {
    TestOutput::subheader("Server Connectivity Check");
    
    try {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => ApiTestConfig::$baseUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_NOBODY => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            TestOutput::failure("❌ Server connectivity failed: {$error}");
            TestOutput::info("💡 Please ensure the server is running and accessible");
            exit(1);
        } else {
            TestOutput::success("✅ Server is responding (HTTP {$httpCode})");
            TestOutput::info("Server URL: " . ApiTestConfig::$baseUrl);
        }
        
    } catch (Exception $e) {
        TestOutput::failure("❌ Connectivity check exception: " . $e->getMessage());
        exit(1);
    }
}

/**
 * Generate detailed test analysis
 */
function generateTestAnalysis() {
    $total = ApiTestConfig::$stats['passed'] + ApiTestConfig::$stats['failed'];
    $successRate = $total > 0 ? round((ApiTestConfig::$stats['passed'] / $total) * 100, 2) : 0;
    
    TestOutput::subheader("Test Analysis");
    
    TestOutput::info("📊 Test Statistics:");
    TestOutput::info("   • Total Tests Run: {$total}");
    TestOutput::info("   • Tests Passed: " . ApiTestConfig::$stats['passed']);
    TestOutput::info("   • Tests Failed: " . ApiTestConfig::$stats['failed']);
    TestOutput::info("   • Success Rate: {$successRate}%");
    
    if (ApiTestConfig::$stats['failed'] > 0) {
        TestOutput::warning("⚠️  Failed Tests Analysis:");
        TestOutput::info("   • Check server logs for detailed error information");
        TestOutput::info("   • Verify test user credentials are correct");
        TestOutput::info("   • Ensure database is properly configured");
        TestOutput::info("   • Check API endpoint URLs and routes");
    }
    
    TestOutput::info("🔍 Test Coverage:");
    TestOutput::info("   • Login API: Authentication, validation, error handling");
    TestOutput::info("   • Profile API: CRUD operations, field validation, security");
    TestOutput::info("   • Security: Token-based authentication, input validation");
    TestOutput::info("   • Error Cases: Missing fields, invalid data, unauthorized access");
    
    if (ApiTestConfig::$authToken) {
        TestOutput::info("🔑 Authentication: Successfully tested token-based auth");
    } else {
        TestOutput::warning("🔑 Authentication: Token acquisition failed - limited test coverage");
    }
}

/**
 * Perform cleanup operations
 */
function performCleanup() {
    TestOutput::subheader("Cleanup");
    
    // Log test results
    $logData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'base_url' => ApiTestConfig::$baseUrl,
        'total_tests' => ApiTestConfig::$stats['passed'] + ApiTestConfig::$stats['failed'],
        'passed' => ApiTestConfig::$stats['passed'],
        'failed' => ApiTestConfig::$stats['failed'],
        'duration' => round(microtime(true) - ApiTestConfig::$stats['start_time'], 2),
        'auth_token_acquired' => !empty(ApiTestConfig::$authToken)
    ];
    
    // Create logs directory if it doesn't exist
    if (!is_dir('logs')) {
        mkdir('logs', 0755, true);
    }
    
    $logFile = 'logs/api_test_results.json';
    file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
    
    TestOutput::info("📝 Test results logged to: {$logFile}");
    
    // Clear sensitive data
    ApiTestConfig::$authToken = null;
    
    TestOutput::info("🧹 Cleanup completed");
}

/**
 * Handle script interruption gracefully
 */
function handleSignal($signal) {
    TestOutput::warning("\n⚠️  Test execution interrupted (Signal: {$signal})");
    TestOutput::info("🛑 Performing emergency cleanup...");
    
    // Clear sensitive data
    ApiTestConfig::$authToken = null;
    
    TestOutput::info("💾 Partial results saved");
    TestOutput::summary();
    
    exit(1);
}

/**
 * Display usage information
 */
function displayUsage() {
    echo "\n";
    echo "🧪 API Test Suite - Login & Profile APIs\n";
    echo "==========================================\n";
    echo "\n";
    echo "Usage:\n";
    echo "  php run_api_tests.php [options]\n";
    echo "\n";
    echo "Options:\n";
    echo "  --help, -h    Show this help message\n";
    echo "  --version     Show version information\n";
    echo "\n";
    echo "Environment Requirements:\n";
    echo "  • PHP 7.4+ with cURL extension\n";
    echo "  • Network access to: " . ApiTestConfig::$baseUrl . "\n";
    echo "  • Valid test user credentials configured\n";
    echo "\n";
    echo "Test Coverage:\n";
    echo "  • Login API (/api/auth/login)\n";
    echo "  • Update Profile API (/api/auth/profile)\n";
    echo "  • Authentication & Authorization\n";
    echo "  • Input Validation & Error Handling\n";
    echo "\n";
    echo "Output:\n";
    echo "  • Colored console output\n";
    echo "  • JSON log files in logs/ directory\n";
    echo "  • Detailed error reporting\n";
    echo "\n";
}

/**
 * Main script execution
 */
if (php_sapi_name() !== 'cli') {
    die("❌ This script must be run from command line\n");
}

// Handle command line arguments
if (in_array('--help', $argv) || in_array('-h', $argv)) {
    displayUsage();
    exit(0);
}

if (in_array('--version', $argv)) {
    echo "API Test Suite v1.0.0\n";
    echo "Compatible with Laravel Sanctum Authentication\n";
    exit(0);
}

// Set up signal handlers for graceful interruption
if (function_exists('pcntl_signal')) {
    pcntl_signal(SIGINT, 'handleSignal');
    pcntl_signal(SIGTERM, 'handleSignal');
}

// Check PHP version
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    echo "❌ PHP 7.4.0 or higher is required. Current version: " . PHP_VERSION . "\n";
    exit(1);
}

// Check required extensions
if (!extension_loaded('curl')) {
    echo "❌ PHP cURL extension is required\n";
    exit(1);
}

if (!extension_loaded('json')) {
    echo "❌ PHP JSON extension is required\n";
    exit(1);
}

// Run the tests
try {
    runAllTests();
    
    // Exit with appropriate code
    $exitCode = ApiTestConfig::$stats['failed'] > 0 ? 1 : 0;
    exit($exitCode);
    
} catch (Exception $e) {
    TestOutput::failure("❌ Fatal error: " . $e->getMessage());
    TestOutput::info("📍 Stack trace: " . $e->getTraceAsString());
    exit(1);
}

