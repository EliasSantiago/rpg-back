<?php

namespace App\Repositories\Eloquent;

use App\Models\Task as Model;
use App\Repositories\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
  private $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function index(): object
  {
    return $this->model->paginate(20);
  }

  public function store(array $data): object
  {
    return $this->model->create($data);
  }

  public function destroy(int $idTask): bool
  {
    return $this->model->destroy($idTask) > 0;
  }

  public function show(int $idTask): object | null 
  {
    return $this->model->find($idTask);
  }

  public function update(int $idTask, array $data): object | null
  {
    $task = $this->model->find($idTask);
    $task->update($data);
    return $task;
  }
}