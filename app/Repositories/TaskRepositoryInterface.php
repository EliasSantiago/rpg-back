<?php

namespace App\Repositories;

interface TaskRepositoryInterface
{
  public function index(): object;
  public function store(array $data): object;
  public function destroy(int $idTask): bool;
  public function show(int $idTask): object | null;
  public function update(int $idTask, array $data): object | null;
}