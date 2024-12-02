<?php

namespace App\Repositories\Eloquent;

use App\Models\User as Model;
use App\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
  private $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function index()
  {
    return $this->model->with(['rpgClass:id,name'])->orderBy('xp', 'desc')->paginate(200);
  }

  public function getUserById($userId)
  {
    return $this->model->findOrFail($userId);
  }

  public function update($data, $userId)
  {
    $user = $this->model->findOrFail($userId);
    $user->update($data);
    return $user;
  }

  public function confirmAll($data)
  {
      $confirmed = (bool) $data['confirmed'];
      $this->model->where('confirmed', '!=', $confirmed)->update(['confirmed' => $confirmed]);
  }
}
