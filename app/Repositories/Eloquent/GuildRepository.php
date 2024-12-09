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

  public function update(array $data, $guildId): Guilds
  {
    $guild = $this->model->findOrFail($guildId);
    $guild->update($data);
    return $guild;
  }

  public function delete($guildId): void
  {
      $guild = $this->model->findOrFail($guildId);
      $guild->users()->detach();
      $guild->delete();
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

  public function getPlayersConfirmed(): Collection
  {
    return User::where('confirmed', true)->get();
  }
}
