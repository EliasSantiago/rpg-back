<?php

namespace App\Services;

use App\Exceptions\GuildFullException;
use App\Exceptions\NoPlayersConfirmedException;
use App\Exceptions\UserAlreadyInGuildException;
use App\Exceptions\UserNotInGuildException;
use App\Models\Guilds;
use App\Repositories\GuildRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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

        $this->validateGuildCapacity($guild);

        if ($guild->users()->where('user_id', $data['user_id'])->exists()) {
            throw new UserAlreadyInGuildException();
        }

        $guild->users()->attach($data['user_id']);

        return $guild;
    }

    public function removeUserFromGuild(int $userId, int $guildId)
    {
        $guild = $this->repository->show($guildId);

        if (!$guild->users()->where('user_id', $userId)->exists()) {
            throw new UserNotInGuildException();
        }

        $guild->users()->detach($userId);

        return $guild;
    }

    public function balanceGuilds()
    {
        $guilds = $this->repository->getGuildsWithUsers();
        $players = $this->repository->getPlayersConfirmed();

        if ($players->isEmpty()) {
            throw new NoPlayersConfirmedException;
        }

        $classRequirements = [
            'Clérigo' => 1,
            'Guerreiro' => 1,
            'Mago' => 1,
        ];

        $this->distributeClassesAmongGuilds($guilds, $players, $classRequirements);
        $this->distributeExperiencePoints($guilds);
    }

    private function distributeClassesAmongGuilds(Collection $guilds, Collection &$players, array $classRequirements)
    {
        foreach ($guilds as $guild) {
            $guild->users()->detach();

            foreach ($classRequirements as $class => $min) {
                $neededPlayers = $players
                    ->filter(fn($player) => $player->rpgClass->name === $class)
                    ->sortByDesc('xp')
                    ->take($min);

                
                foreach ($neededPlayers as $player) {
                    $this->addPlayerToGuild($player, $guild);
                    $players = $players->reject(fn($remainingPlayer) => $remainingPlayer->id === $player->id);
                }
            }
        }

        // Após alocar os jogadores mínimos, preencher as guildas restantes
        foreach ($guilds as $guild) {
            while (!$this->isGuildFull($guild) && $players->isNotEmpty()) {
                $player = $players->shift(); // Retira o primeiro jogador disponível
                $this->addPlayerToGuild($player, $guild);
            }
        }
    }

    private function distributeExperiencePoints(Collection $guilds)
    {
        $maxExecutionTime = now()->addSeconds(10);

        while (true) {
            $guilds = $guilds->sortByDesc(fn($guild) => $guild->users->sum('xp'));

            $maxGuild = $guilds->first();
            $minGuild = $guilds->last();

            $xpDifference = $maxGuild->users->sum('xp') - $minGuild->users->sum('xp');

            if ($xpDifference <= 10 || now()->greaterThanOrEqualTo($maxExecutionTime)) {
                break;
            }

            $playerToMove = $this->findPlayerToBalance($maxGuild, $minGuild);

            if (!$playerToMove) {
                break;
            }

            $this->movePlayerBetweenGuilds($playerToMove, $maxGuild, $minGuild);
        }
    }

    private function findPlayerToBalance(Guilds $maxGuild, Guilds $minGuild)
    {
        return $maxGuild->users
            ->sortByDesc('xp')
            ->first(fn($player) => $this->canMovePlayer($player, $minGuild));
    }

    private function movePlayerBetweenGuilds($player, Guilds $fromGuild, Guilds $toGuild)
    {
        $fromGuild->users()->detach($player->id);
        $toGuild->users()->attach($player->id);
    }

    private function canMovePlayer($player, Guilds $guild): bool
    {
        $playerClass = $player->rpgClass->name;
        $classCount = $guild->users->where('rpgClass.name', $playerClass)->count();

        return $classCount === 0; // Move apenas se a guilda precisar da classe
    }

    private function addPlayerToGuild($player, Guilds $guild)
    {
        $guild->users()->attach($player->id);
    }

    private function validateGuildCapacity(Guilds $guild): void
    {
        if ($this->isGuildFull($guild)) {
            throw new GuildFullException('A guilda atingiu o número máximo de jogadores.');
        }
    }

    private function isGuildFull(Guilds $guild): bool
    {
        return $guild->users()->count() >= $guild->max_players;
    }

    public function updateMaxPlayers(int $guildId, int $maxPlayers): Guilds
    {
        $guild = $this->repository->show($guildId);

        $updatedData = ['max_players' => $maxPlayers];

        return $this->repository->updateMaxPlayers($guildId, $updatedData);
    }
}
