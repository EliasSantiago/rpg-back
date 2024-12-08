<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
  private $repository;

  public function __construct(UserRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function index(): LengthAwarePaginator
  {
    return $this->repository->index();
  }

  public function update($data, $userId): User
  {
    return $this->repository->update($data, $userId);
  }

  public function changeConfirmationAll($data): void
  {
    $this->repository->changeConfirmationAll($data);
  }

  public function changeConfirmation($data, $userId): void
  {
    $this->repository->changeConfirmation($data, $userId);
  }

  public function show($userId): User
  {
    return $this->repository->show($userId);
  }

  public function delete($userId): void
  {
    $this->repository->delete($userId);
  }
}
