<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => true
        ]);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The provided credentials are incorrect.'
            ]);
    }

    public function test_user_cannot_login_with_nonexistent_email()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The provided credentials are incorrect.'
            ]);
    }

    public function test_login_validation_requires_email()
    {
        $response = $this->postJson('/api/auth/login', [
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_login_validation_requires_password()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_login_requires_valid_email_format()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'invalid-email',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_inactive_user_cannot_login()
    {
        // Update user status to inactive
        User::where('email', 'test@example.com')
            ->update(['status' => false]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The provided credentials are incorrect.'
            ]);
    }

    public function test_user_can_login_with_device_name()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'device_name' => 'Test Device'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'user'
            ]);
    }
}