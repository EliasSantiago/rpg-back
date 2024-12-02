<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    public function test_register_with_valid_credentials()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/api/v1/register', $userData);

        $response->assertStatus(201);

        $response->assertJson([
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email']
        ]);
    }

    public function test_register_with_invalid_credentials()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/api/v1/register', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'short',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'testabc@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/api/v1/login', [
            'email' => 'testabc@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at', 'token_type', 'token']);

        $token = $response->json('token');
        $authenticatedResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->json('GET', '/api/v1/information');

        $authenticatedResponse->assertStatus(200);
    }

    public function test_login_with_invalid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/api/v1/login', [
            'email' => 'invalid@example.com',
            'password' => 'senha_incorreta',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized', 'error' => true]);

        $this->assertGuest();
    }
}
