<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
  public function index(): LengthAwarePaginator;
  public function getUserById($userId): User;
  public function update($data, $userId): User;
  public function confirmAll($data): void;
}