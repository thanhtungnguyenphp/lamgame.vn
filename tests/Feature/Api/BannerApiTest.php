<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use LamGame\Banner\Models\Banner;
use Tests\TestCase;
use Webkul\Core\Models\Channel;

class BannerApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $baseUrl = '/api/banners';

    protected function setUp(): void
    {
        parent::setUp();
        
        // Clear cache before each test
        Cache::flush();
    }

    /**
     * Test successful banner deletion.
     */
    public function test_successful_banner_deletion(): void
    {
        // Create a test banner
        $banner = $this->createTestBanner();
        
        // Make DELETE request
        $response = $this->deleteJson($this->baseUrl . '/' . $banner->id);
        
        // Assert response
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'message' => 'Banner deleted successfully',
                'data' => [
                    'id' => $banner->id,
                    'name' => $banner->name,
                ],
            ]);
        
        // Assert banner is deleted from database
        $this->assertDatabaseMissing('banners', [
            'id' => $banner->id,
        ]);
    }

    /**
     * Test deletion of non-existent banner.
     */
    public function test_delete_non_existent_banner(): void
    {
        $nonExistentId = 99999;
        
        $response = $this->deleteJson($this->baseUrl . '/' . $nonExistentId);
        
        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'success' => false,
                'message' => 'Banner not found',
            ]);
    }

    /**
     * Test deletion with invalid banner ID.
     */
    public function test_delete_with_invalid_banner_id(): void
    {
        // Test with negative ID (should fail route constraint)
        $response = $this->deleteJson($this->baseUrl . '/-1');
        $response->assertStatus(Response::HTTP_NOT_FOUND); // Route not found
        
        // Test with non-numeric ID (should fail route constraint)
        $response = $this->deleteJson($this->baseUrl . '/invalid-id');
        $response->assertStatus(Response::HTTP_NOT_FOUND); // Route not found
        
        // Test with zero ID (should fail route constraint)
        $response = $this->deleteJson($this->baseUrl . '/0');
        $response->assertStatus(Response::HTTP_NOT_FOUND); // Route not found
    }

    /**
     * Test that cache is cleared after banner deletion.
     */
    public function test_cache_is_cleared_after_banner_deletion(): void
    {
        $banner = $this->createTestBanner();
        
        // Pre-populate cache by making a GET request
        $this->getJson($this->baseUrl)
            ->assertStatus(Response::HTTP_OK);
        
        // Verify cache exists
        $cacheKey = 'banner_display:pos_all:dev_all:ch_all:loc_all';
        $this->assertTrue(Cache::has($cacheKey));
        
        // Delete the banner
        $response = $this->deleteJson($this->baseUrl . '/' . $banner->id);
        $response->assertStatus(Response::HTTP_OK);
        
        // Verify cache is cleared
        $this->assertFalse(Cache::has($cacheKey));
    }

    /**
     * Test banner deletion response structure.
     */
    public function test_banner_deletion_response_structure(): void
    {
        $banner = $this->createTestBanner();
        
        $response = $this->deleteJson($this->baseUrl . '/' . $banner->id);
        
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'position',
                    'type',
                    'deleted_at',
                ],
            ]);
    }

    /**
     * Test multiple banner deletions.
     */
    public function test_multiple_banner_deletions(): void
    {
        $banner1 = $this->createTestBanner(['name' => 'Banner 1']);
        $banner2 = $this->createTestBanner(['name' => 'Banner 2']);
        
        // Delete first banner
        $response1 = $this->deleteJson($this->baseUrl . '/' . $banner1->id);
        $response1->assertStatus(Response::HTTP_OK);
        
        // Delete second banner
        $response2 = $this->deleteJson($this->baseUrl . '/' . $banner2->id);
        $response2->assertStatus(Response::HTTP_OK);
        
        // Verify both banners are deleted
        $this->assertDatabaseMissing('banners', ['id' => $banner1->id]);
        $this->assertDatabaseMissing('banners', ['id' => $banner2->id]);
    }

    /**
     * Test attempting to delete same banner twice.
     */
    public function test_delete_same_banner_twice(): void
    {
        $banner = $this->createTestBanner();
        
        // First deletion should succeed
        $response1 = $this->deleteJson($this->baseUrl . '/' . $banner->id);
        $response1->assertStatus(Response::HTTP_OK);
        
        // Second deletion should fail
        $response2 = $this->deleteJson($this->baseUrl . '/' . $banner->id);
        $response2->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'success' => false,
                'message' => 'Banner not found',
            ]);
    }

    /**
     * Test banner deletion performance with large ID.
     */
    public function test_banner_deletion_with_large_id(): void
    {
        $largeId = 2147483647; // Max 32-bit integer
        
        $response = $this->deleteJson($this->baseUrl . '/' . $largeId);
        
        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'success' => false,
                'message' => 'Banner not found',
            ]);
    }

    /**
     * Create a test banner for testing purposes.
     */
    private function createTestBanner(array $overrides = []): Banner
    {
        // Create a default channel if it doesn't exist
        $channel = Channel::first() ?? Channel::factory()->create([
            'code' => 'default',
            'name' => 'Default Channel',
        ]);

        $defaultData = [
            'name' => $this->faker->sentence(3),
            'type' => 'image',
            'position' => 'homepage_hero',
            'device_type' => 'all',
            'title' => $this->faker->sentence(4),
            'content' => $this->faker->paragraph(),
            'image' => $this->faker->imageUrl(),
            'link' => $this->faker->url(),
            'target' => '_self',
            'sort_order' => 0,
            'status' => true,
            'channel_id' => $channel->id,
            'locale' => 'vi',
        ];

        return Banner::factory()->create(array_merge($defaultData, $overrides));
    }
}