<?php

namespace App\Services;

use App\Repositories\AuthRepositoryInterface;

class AuthService
{
  private $repository;

  public function __construct(AuthRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function register(array $data): object
  {
    $data['xp'] = 0;
    return $this->repository->register($data);
  }

  public function login(array $data): object
  {
    return $this->repository->login($data);
  }

  public function information(): object
  {
    return $this->repository->information();
  }
}