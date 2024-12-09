<?php

namespace App\Repositories\Eloquent;

use App\Models\Guilds;
use App\Models\User as Model;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
  private $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function index(): LengthAwarePaginator
  {
    return $this->model->with(['rpgClass:id,name'])->orderBy('xp', 'desc')->paginate(200);
  }

  public function delete($userId): void
  {
    $user = $this->model->findOrFail($userId);
    $user->delete();
  }

  public function show($userId): User
  {
    return $this->model->with(['rpgClass:id,name'])->findOrFail($userId);
  }

  public function update($data, $userId): User
  {
    $user = $this->model->findOrFail($userId);
    $user->update($data);
    return $user;
  }

  public function changeConfirmationAll($data): void
  {
      $confirmed = (bool) $data['confirmed'];
  
      $this->model->where('confirmed', '!=', $confirmed)->update(['confirmed' => $confirmed]);
      if (!$confirmed) {
          $guilds = Guilds::all();
  
          foreach ($guilds as $guild) {
              $guild->users()->detach();
          }
      }
  }
  
  public function changeConfirmation($data, $userId): void
  {
    $confirmed = (bool) $data['confirmed'];
    $this->model->where('id', '=', $userId)->update(['confirmed' => $confirmed]);
  }
}
