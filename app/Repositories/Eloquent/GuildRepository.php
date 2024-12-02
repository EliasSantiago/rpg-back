<?php

namespace App\Repositories\Eloquent;

use App\Models\Guilds as Model;
use App\Repositories\GuildRepositoryInterface;

class GuildRepository implements GuildRepositoryInterface
{
  private $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function show($guildId)
  {
    return $this->model::findOrFail($guildId);
  }

  public function getAllGuilds()
  {
    return $this->model::with(['users.rpgClass:id,name'])->paginate(200);
  }

  public function store(array $data): ?object
  {
    return $this->model->create($data);
  }
}
