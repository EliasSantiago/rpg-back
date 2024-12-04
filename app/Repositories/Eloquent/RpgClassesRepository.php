<?php

namespace App\Repositories\Eloquent;

use App\Models\RpgClass as Model;
use App\Models\RpgClass;
use App\Repositories\RpgClassesRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class RpgClassesRepository implements RpgClassesRepositoryInterface
{
  private $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function getAllClasses(): LengthAwarePaginator
  {
    return $this->model->paginate(200);
  }

  public function store(array $data): ?RpgClass
  {
    return $this->model->create($data);
  }
}
