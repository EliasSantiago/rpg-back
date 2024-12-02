<?php

namespace App\Repositories;

interface RpgClassesRepositoryInterface
{
  public function getAllClasses();
  public function store(array $data): object | null;
}