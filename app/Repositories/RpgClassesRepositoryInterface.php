<?php

namespace App\Repositories;

use App\Models\RpgClass;
use Illuminate\Pagination\LengthAwarePaginator;

interface RpgClassesRepositoryInterface
{
  public function getAllClasses(): LengthAwarePaginator;
  public function store(array $data): ?RpgClass;
}