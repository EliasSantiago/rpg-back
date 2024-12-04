<?php

namespace App\Services;

use App\Models\RpgClass;
use App\Repositories\RpgClassesRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class RpgClassesService
{
  private $repository;

  public function __construct(RpgClassesRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function getAllClasses(): LengthAwarePaginator
  {
    return $this->repository->getAllClasses();
  }

  public function store(array $data): ?RpgClass
  {
    return $this->repository->store($data);
  }
}