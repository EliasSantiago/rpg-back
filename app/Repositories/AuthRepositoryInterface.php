<?php

namespace App\Repositories;

interface AuthRepositoryInterface
{
  public function register(array $data): object | null;
  public function login(array $data): object;
  public function information(): object;
}