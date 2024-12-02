<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    public function test_index_returns_list_of_tasks()
    {
        $user = User::factory()->create();
        Task::factory(5)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('auth_token')->accessToken,
            'Accept' => 'application/json',
        ])->json('GET', '/api/v1/tasks');

        $response->assertStatus(200);

        $response->assertJsonCount(5, 'data');
    }

    public function test_store_with_valid_params()
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

        $token = $response->json('token');

        $taskData = [
            'user_id'       => 1,
            'title'         => 'Planning meeting',
            'description'   => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
        ];

        $authenticatedResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->json('POST', '/api/v1/tasks', $taskData);

        $authenticatedResponse->assertStatus(201);

        $authenticatedResponse->assertJson([
            'title' => $taskData['title'],
            'description' => $taskData['description'],
        ]);

        $this->assertDatabaseHas('tasks', [
            'title'         => $taskData['title'],
            'description'   => $taskData['description']
        ]);
    }

    public function test_store_with_invalid_params()
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

        $token = $response->json('token');

        $invalidTaskData = [
            'user_id' => 1,
            'title' => '',
            'description' => '',
        ];

        $authenticatedResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->json('POST', '/api/v1/tasks', $invalidTaskData);

        $authenticatedResponse->assertStatus(422);

        $authenticatedResponse->assertJsonValidationErrors([
            'title', 'description'
        ]);

        $this->assertDatabaseMissing('tasks', [
            'title' => $invalidTaskData['title'],
            'description' => $invalidTaskData['description']
        ]);
    }

    public function test_destroy_with_valid_param_for_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('auth_token')->accessToken,
            'Accept' => 'application/json',
        ])->json('DELETE', "/api/v1/tasks/{$task->id}");

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);

        $response->assertStatus(204);
    }

    public function test_destroy_with_invalid_param_for_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('auth_token')->accessToken,
            'Accept' => 'application/json',
        ])->json('DELETE', "/api/v1/tasks/invalid_id");

        $response->assertStatus(404);
    }

    public function test_show_with_valid_param_for_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'asdfasdf',
            'description' => 'asdasf',
        ]);

        $this->actingAs($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('auth_token')->accessToken,
            'Accept' => 'application/json',
        ])->get("/api/v1/tasks/{$task->id}");

        $response->assertStatus(200);

        $response->assertJson([
            'id' => $task->id,
            'user_id' => $user->id,
            'title' => 'asdfasdf',
            'description' => 'asdasf',
            'status' => 'pendente',
        ]);
    }

    public function test_show_with_invalid_param_for_task()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('auth_token')->accessToken,
            'Accept' => 'application/json',
        ])->get("/api/v1/tasks/invalid_id");

        $response->assertStatus(422);

        $response->assertJson([
            'message' => 'Invalid ID',
            'error' => 'true',
        ]);
    }

    public function test_update_with_valid_param_for_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $updatedData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => 'pendente',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('auth_token')->accessToken,
            'Accept' => 'application/json',
        ])->json('PUT', "/api/v1/tasks/{$task->id}", $updatedData);

        $response->assertStatus(200);

        $response->assertJson([
            'title' => $updatedData['title'],
            'description' => $updatedData['description'],
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $updatedData['title'],
            'description' => $updatedData['description'],
        ]);
    }

    public function test_update_with_invalid_param_for_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $invalidData = [
            'title' => '',
            'description' => 'Updated Description',
            'status' => 'pendente',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('auth_token')->accessToken,
            'Accept' => 'application/json',
        ])->json('PUT', "/api/v1/tasks/{$task->id}", $invalidData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
        $response->assertJson(['errors' => ['title' => ['O campo título é obrigatório.']]]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
        ]);
    }
}
