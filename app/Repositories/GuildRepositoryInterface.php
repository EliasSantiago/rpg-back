<?php

namespace App\Repositories;

use App\Models\Guilds;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface GuildRepositoryInterface
{
    public function show(int $guildId): Guilds;
    public function getAllGuilds(): LengthAwarePaginator;
    public function store(array $data): ?object;
    public function update(array $data, $guildId): Guilds;
    public function delete($guildId): void;
    public function getGuildsWithUsers(): Collection;
    public function getPlayersConfirmed(): Collection;
    public function updateMaxPlayers(int $guildId, array $updatedData): Guilds;
}