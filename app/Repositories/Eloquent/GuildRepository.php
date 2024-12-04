<?php

namespace App\Repositories\Eloquent;

use App\Models\Guilds as Model;
use App\Models\Guilds;
use App\Models\User;
use App\Repositories\GuildRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class GuildRepository implements GuildRepositoryInterface
{
  private $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function show(int $guildId): Guilds
  {
    return $this->model::findOrFail($guildId);
  }

  public function getAllGuilds(): LengthAwarePaginator
  {
    return $this->model::with(['users.rpgClass:id,name'])->paginate(200);
  }

  public function store(array $data): ?object
  {
    return $this->model->create($data);
  }

  public function updateMaxPlayers(int $guildId, array $updatedData): Guilds
  {
    $guild = Guilds::findOrFail($guildId);
    $guild->update($updatedData);
    return $guild;
  }

  public function getGuildsWithUsers(): Collection
  {
    return Guilds::with('users.rpgClass')->get();
  }

  public function getPlayersNotInGuild(): Collection
  {
    return User::whereDoesntHave('guilds')->get();
  }
}
