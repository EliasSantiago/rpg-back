<?php

namespace App\Services;

use App\Exceptions\GuildFullException;
use App\Exceptions\UserAlreadyInGuildException;
use App\Models\Guilds;
use App\Repositories\GuildRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class GuildService
{
    private $repository;

    public function __construct(GuildRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllGuilds(): LengthAwarePaginator
    {
        return $this->repository->getAllGuilds();
    }

    public function show(int $guildId): Guilds
    {
        return $this->repository->show($guildId);
    }

    public function store(array $data): object
    {
        return $this->repository->store($data);
    }

    public function addUserToGuild(array $data, int $guildId)
    {
        $guild = $this->repository->show($guildId);

        if ($this->isGuildFull($guild)) {
            throw new GuildFullException('A guilda atingiu o número máximo de jogadores.');
        }

        if ($guild->users()->where('user_id', $data['user_id'])->exists()) {
            throw new UserAlreadyInGuildException(); // Lança a exceção personalizada
        }

        $guild->users()->attach($data['user_id']);

        return $guild;
    }

    private function isGuildFull(Guilds $guild): bool
    {
        return $guild->users()->count() >= $guild->max_players;
    }

    public function balanceGuilds()
    {
        $guilds = $this->repository->getGuildsWithUsers();
        $players = $this->repository->getPlayersNotInGuild();

        $this->distributeClassesAmongGuilds($guilds, $players);
        $this->distributeExperiencePoints($guilds);
    }

    private function distributeClassesAmongGuilds($guilds, $players)
    {
        $classRequirements = [
            'Clérigo' => 1,
            'Guerreiro' => 1,
            'Mago' => 1,
        ];

        foreach ($guilds as $guild) {
            foreach ($classRequirements as $class => $min) {
                $neededPlayers = $players->filter(function ($player) use ($class) {
                    return $player->rpgClass->name === $class;
                })->take($min);

                foreach ($neededPlayers as $player) {
                    if ($guild->users->contains($player->id)) {
                        continue;
                    }

                    $guild->users()->attach($player->id);
                    $players = $players->reject(function ($remainingPlayer) use ($player) {
                        return $remainingPlayer->id === $player->id;
                    });
                }
            }
        }
    }

    private function distributeExperiencePoints($guilds)
    {
        $maxExecutionTime = 5;
        $startTime = microtime(true);

        foreach ($guilds as $guild) {
            $guild->total_xp = $guild->users->sum('xp');
        }

        $sortedGuilds = $guilds->sortBy('total_xp')->values();

        while (true) {
            $currentTime = microtime(true);
            $elapsedTime = $currentTime - $startTime;

            if ($elapsedTime > $maxExecutionTime) {
                break;
            }

            foreach ($guilds as $guild) {
                $guild->total_xp = $guild->users->sum('xp');
            }

            $sortedGuilds = $guilds->sortBy('total_xp')->values();

            $minGuild = $sortedGuilds->first();
            $maxGuild = $sortedGuilds->last();

            if (($maxGuild->total_xp - $minGuild->total_xp) <= 10) {
                break;
            }

            $playerToMove = $maxGuild->users->sortByDesc('xp')->first();

            if (!$playerToMove) {
                break;
            }

            if ($minGuild->users()->where('user_id', $playerToMove->id)->exists()) {
                continue;
            }

            $maxGuild->users()->detach($playerToMove->id);
            $minGuild->users()->attach($playerToMove->id);

            $maxGuild->total_xp = $maxGuild->users->sum('xp');
            $minGuild->total_xp = $minGuild->users->sum('xp');
        }
    }

    public function updateMaxPlayers(int $guildId, int $maxPlayers): Guilds
    {
        if (!$this->repository->show($guildId)) {
            throw new GuildFullException('Guilda não encontrada.');
        }

        $updatedData = ['max_players' => $maxPlayers];

        return $this->repository->updateMaxPlayers($guildId, $updatedData);
    }
}
