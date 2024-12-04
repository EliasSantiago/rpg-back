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
    public function getGuildsWithUsers(): Collection;
    public function getPlayersNotInGuild(): Collection;
    public function updateMaxPlayers(int $guildId, array $updatedData): Guilds;
}