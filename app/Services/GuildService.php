<?php

namespace App\Services;

use App\Models\Guilds;
use App\Repositories\GuildRepositoryInterface;

class GuildService
{
    private $repository;

    public function __construct(GuildRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllGuilds()
    {
        return $this->repository->getAllGuilds();
    }

    public function show($guildId)
    {
        return $this->repository->show($guildId);
    }

    public function store(array $data): object
    {
        return $this->repository->store($data);
    }

    public function addUserToGuild(array $data, $guildId)
    {
        $guild = Guilds::findOrFail($guildId);

        if ($guild->users()->count() >= $guild->max_players) {
            throw new \Exception('A guilda atingiu o número máximo de jogadores.', 400);
        }

        $guild->users()->attach($data['user_id']);

        if ($guild->users()->count() >= $guild->max_players) {
            $this->balanceGuilds();
        }

        return $guild;
    }

    public function balanceGuilds()
    {
        $guilds = Guilds::with('users.rpgClass')->get();
        $players = $this->getAllPlayersNotInGuild();

        $this->distributeClasses($guilds, $players);

        $this->distributeXP($guilds);
    }

    private function distributeClasses($guilds, $players)
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

                $guild->users()->attach($neededPlayers->pluck('id'));
                $players = $players->diff($neededPlayers);
            }
        }
    }

    private function distributeXP($guilds)
    {
        $sortedGuilds = $guilds->sortBy('total_xp')->values();

        while (true) {
            $minGuild = $sortedGuilds->first();
            $maxGuild = $sortedGuilds->last();

            if (($maxGuild->total_xp - $minGuild->total_xp) <= 10) {
                break;
            }

            $playerToMove = $maxGuild->users->sortByDesc('xp')->first();

            if (!$playerToMove) {
                break;
            }

            $maxGuild->users()->detach($playerToMove->id);
            $minGuild->users()->attach($playerToMove->id);

            $maxGuild->total_xp = $maxGuild->users->sum('xp');
            $minGuild->total_xp = $minGuild->users->sum('xp');

            $sortedGuilds = $guilds->sortBy('total_xp')->values();
        }
    }

    private function getAllPlayersNotInGuild()
    {
        return \App\Models\User::where('confirmed', true)
            ->whereDoesntHave('guilds')
            ->get();
    }

    public function updateMaxPlayers($guildId, $maxPlayers)
    {
        $guild = Guilds::findOrFail($guildId);
        $guild->max_players = $maxPlayers;
        $guild->save();

        if ($guild->users()->count() > $maxPlayers) {
            $this->balanceGuilds();
        }

        return $guild;
    }
}
