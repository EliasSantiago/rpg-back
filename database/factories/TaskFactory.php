<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;

class TaskFactory extends Factory
{
  protected $model = Task::class;

  public function definition()
  {
    return [
      'title' => $this->faker->sentence,
      'description' => $this->faker->paragraph,
      'user_id' => 1,
    ];
  }
}
