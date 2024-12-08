<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
  public function index(): LengthAwarePaginator;
  public function update($data, $userId): User;
  public function changeConfirmationAll($data): void;
  public function changeConfirmation($data, $userId): void;
  public function show($userId): User;
  public function delete($userId): void;
}