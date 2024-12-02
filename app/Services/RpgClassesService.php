<?php

namespace App\Services;

use App\Repositories\RpgClassesRepositoryInterface;

class RpgClassesService
{
  private $repository;

  public function __construct(RpgClassesRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function getAllClasses() {
    return $this->repository->getAllClasses();
  }

  public function store(array $data): object
  {
    return $this->repository->store($data);
  }
}