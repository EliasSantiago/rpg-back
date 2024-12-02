<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;

class UserService
{
  private $repository;

  public function __construct(UserRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function index()
  {
    return $this->repository->index();
  }

  public function update($data, $userId)
  {
    return $this->repository->update($data, $userId);
  }

  public function confirmAll($data)
  {
    return $this->repository->confirmAll($data);
  }
}
