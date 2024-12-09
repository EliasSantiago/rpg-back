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

    public function update(array $data, $guildId): Guilds
    {
        return $this->repository->update($data, $guildId);
    }

    public function delete($guildId): void
    {
        $this->repository->delete($guildId);
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
                    if (!$this->isPlayerInAnyGuild($player) && !$this->isGuildFull($guild)) {
                        $this->addPlayerToGuild($player, $guild);
                        $players = $players->reject(fn($remainingPlayer) => $remainingPlayer->id === $player->id);
                    }
                }
            }
        }

        foreach ($guilds as $guild) {
            while (!$this->isGuildFull($guild) && $players->isNotEmpty()) {
                $player = $players->shift();
                if (!$this->isPlayerInAnyGuild($player)) {
                    $this->addPlayerToGuild($player, $guild);
                }
            }
        }
    }

    private function distributeExperiencePoints(Collection $guilds)
    {
        $maxExecutionTime = now()->addSeconds(2);
        $totalXp = $guilds->sum(fn($guild) => $guild->users->sum('xp'));
        $totalPlayers = $guilds->sum(fn($guild) => $guild->users->count());

        if ($totalPlayers == 0) {
            return;
        }

        $averageXpPerPlayer = $totalXp / $totalPlayers;

        while (true) {
            $guilds = $guilds->sortByDesc(fn($guild) => $guild->users->sum('xp'));

            $xpDifference = $guilds->first()->users->sum('xp') - $guilds->last()->users->sum('xp');
            if ($xpDifference <= 10 || now()->greaterThanOrEqualTo($maxExecutionTime)) {
                break;
            }

            $maxGuild = $guilds->first();
            $minGuild = $guilds->last();

            $playerToMove = $this->findPlayerToBalanceByXp($maxGuild, $minGuild, $averageXpPerPlayer);

            if (!$playerToMove) {
                break;
            }

            $this->movePlayerBetweenGuilds($playerToMove, $maxGuild, $minGuild);
        }
    }


    private function findPlayerToBalanceByXp(Guilds $maxGuild, Guilds $minGuild, float $averageXpPerPlayer)
    {
        $playersToMove = $maxGuild->users
            ->sortByDesc('xp')
            ->filter(fn($player) => abs($player->xp - $averageXpPerPlayer) > 10);

        if ($playersToMove->isEmpty()) {
            return null;
        }

        return $playersToMove->first();
    }


    private function movePlayerBetweenGuilds($player, Guilds $fromGuild, Guilds $toGuild)
    {
        if ($this->isPlayerInGuild($player, $fromGuild)) {
            $fromGuild->users()->detach($player->id);
        }

        if (!$this->isGuildFull($toGuild) && !$this->isPlayerInGuild($player, $toGuild)) {
            $toGuild->users()->attach($player->id);
        }
    }

    private function isPlayerInAnyGuild($player): bool
    {
        return $player->guilds()->exists();
    }

    private function isPlayerInGuild($player, Guilds $guild): bool
    {
        return $guild->users()->where('user_id', $player->id)->exists();
    }

    private function addPlayerToGuild($player, Guilds $guild)
    {
        if (!$this->isGuildFull($guild)) {
            $guild->users()->attach($player->id);
        }
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
        $updatedData = ['max_players' => $maxPlayers];
        return $this->repository->updateMaxPlayers($guildId, $updatedData);
    }
}
