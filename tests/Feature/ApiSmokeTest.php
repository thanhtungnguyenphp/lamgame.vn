<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Smoke tests: verify all public API endpoints return expected HTTP status (not 500).
 * These do NOT require database seeding — they just confirm routes are registered and controllers don't crash.
 */
class ApiSmokeTest extends TestCase
{
    // ========== PUBLIC ENDPOINTS (should return 200 or 401, never 500) ==========

    /** @dataProvider publicEndpoints */
    public function test_public_endpoint_does_not_500(string $method, string $uri): void
    {
        $response = $this->{$method}($uri);
        $this->assertNotEquals(500, $response->getStatusCode(), "500 on {$method} {$uri}");
    }

    public static function publicEndpoints(): array
    {
        return [
            // Forum API
            ['get', '/api/v1/forum/posts'],
            ['get', '/api/v1/forum/categories'],
            ['get', '/api/v1/forum/tags'],
            ['get', '/api/v1/forum/trending'],
            ['get', '/api/v1/forum/leaderboard'],

            // Job API v2
            ['get', '/api/v2/jobs'],
            ['get', '/api/v2/jobs/filters'],

            // Reviews (public read)
            ['get', '/api/v1/source-game/1/reviews'],
            ['get', '/api/v1/source-game/1/review-stats'],

            // Sport API (if routes exist)
            ['get', '/api/v1/sport/matches'],
            ['get', '/api/v1/sport/standings'],
            ['get', '/api/v1/sport/highlights'],
            ['get', '/api/v1/sport/articles'],
        ];
    }

    // ========== AUTH-PROTECTED ENDPOINTS (should return 401 without token) ==========

    /** @dataProvider protectedEndpoints */
    public function test_protected_endpoint_returns_401_without_auth(string $method, string $uri): void
    {
        $response = $this->{$method}($uri, [], ['Accept' => 'application/json']);
        $this->assertContains($response->getStatusCode(), [401, 403], "Expected 401/403 on {$method} {$uri}, got {$response->getStatusCode()}");
    }

    public static function protectedEndpoints(): array
    {
        return [
            // Forum auth
            ['post', '/api/v1/forum/posts'],

            // Reviews auth
            ['post', '/api/v1/source-game/1/reviews'],
            ['post', '/api/v1/reviews/1/helpful'],

            // Job auth
            ['post', '/api/v2/jobs'],
        ];
    }

    // ========== MANAGEMENT API (should return 401 without X-Api-Key) ==========

    /** @dataProvider managementEndpoints */
    public function test_management_endpoint_returns_401_without_api_key(string $method, string $uri): void
    {
        $response = $this->{$method}($uri, [], ['Accept' => 'application/json']);
        $this->assertContains($response->getStatusCode(), [401, 403], "Expected 401/403 on {$method} {$uri}, got {$response->getStatusCode()}");
    }

    public static function managementEndpoints(): array
    {
        return [
            // E-Commerce Management
            ['get', '/api/manage/dashboard'],
            ['get', '/api/manage/products'],
            ['get', '/api/manage/orders'],
            ['get', '/api/manage/sellers'],
            ['get', '/api/manage/customers'],

            // Forum Management
            ['get', '/api/manage/forum/dashboard'],
            ['get', '/api/manage/forum/posts'],
            ['get', '/api/manage/forum/categories'],
            ['get', '/api/manage/forum/tags'],
            ['get', '/api/manage/forum/reports'],

            // Job Management
            ['get', '/api/manage/jobs'],
            ['get', '/api/manage/jobs/statistics'],

            // Blog Management
            ['get', '/api/manage/blogs'],
            ['get', '/api/manage/blogs/statistics'],
            ['get', '/api/manage/blogs/categories'],
            ['get', '/api/manage/blogs/tags'],
        ];
    }
}
