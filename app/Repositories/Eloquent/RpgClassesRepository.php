<?php

namespace App\Repositories\Eloquent;

use App\Models\RpgClass as Model;
use App\Repositories\RpgClassesRepositoryInterface;

class RpgClassesRepository implements RpgClassesRepositoryInterface
{
  private $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function getAllClasses()
  {
    return $this->model->paginate(200);
  }

  public function store(array $data): ?object
  {
    return $this->model->create($data);
  }
}
