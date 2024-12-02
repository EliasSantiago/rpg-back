<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskPolicyTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_update_own_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $policy = new TaskPolicy();

        $result = $policy->update($user, $task);

        $this->assertTrue($result);
    }

    public function test_user_cannot_update_other_users_task()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $policy = new TaskPolicy();

        $result = $policy->update($user, $task);

        $this->assertFalse($result);
    }
}
