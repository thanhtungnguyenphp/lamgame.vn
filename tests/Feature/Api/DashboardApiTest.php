<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Webkul\Customer\Models\Customer;
use App\Models\JobApplication;
use Webkul\Product\Models\Product;
use Webkul\Category\Models\Category;
use Laravel\Sanctum\Sanctum;

class DashboardApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $customer;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test customer
        $this->customer = Customer::factory()->create([
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'Customer'
        ]);
        
        // Authenticate customer
        Sanctum::actingAs($this->customer);
    }

    /**
     * Test dashboard API returns correct structure
     */
    public function test_dashboard_api_returns_correct_structure(): void
    {
        $response = $this->getJson('/api/dashboard/');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'recent_jobs',
                    'recent_applications',
                    'statistics' => [
                        'total_jobs',
                        'total_applications',
                        'pending_applications',
                        'jobs_with_applications'
                    ]
                ]
            ]);
    }

    /**
     * Test dashboard API requires authentication
     */
    public function test_dashboard_api_requires_authentication(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid-token'
        ])->getJson('/api/dashboard/');

        $response->assertStatus(401);
    }

    /**
     * Test job applications endpoint
     */
    public function test_job_applications_endpoint(): void
    {
        // Create a product/job
        $product = Product::factory()->create();
        
        // Create job application
        $application = JobApplication::factory()->create([
            'job_id' => $product->id,
            'applicant_user_id' => $this->customer->id
        ]);

        $response = $this->getJson("/api/dashboard/jobs/{$product->id}/applications");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'job',
                    'applications',
                    'statistics'
                ]
            ]);
    }

    /**
     * Test update application status endpoint
     */
    public function test_update_application_status(): void
    {
        // Create a product/job
        $product = Product::factory()->create();
        
        // Create job application
        $application = JobApplication::factory()->create([
            'job_id' => $product->id,
            'applicant_user_id' => $this->customer->id,
            'status' => 'pending'
        ]);

        $response = $this->putJson("/api/dashboard/applications/{$application->id}/status", [
            'status' => 'reviewed',
            'employer_notes' => 'Looking good'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $application->id,
                    'status' => 'reviewed',
                    'employer_notes' => 'Looking good'
                ]
            ]);

        // Verify database was updated
        $this->assertDatabaseHas('job_applications', [
            'id' => $application->id,
            'status' => 'reviewed',
            'employer_notes' => 'Looking good'
        ]);
    }
}
