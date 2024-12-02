<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
  public function index();
  public function getUserById($userId);
  public function update($data, $userId);
  public function confirmAll($data);
}