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

        // Passo 1: Garantir que cada guilda tenha as classes mínimas.
        $this->distributeClasses($guilds, $players);

        // Passo 2: Balancear as guildas por XP.
        $this->distributeXP($guilds);
    }

    private function distributeClasses($guilds, $players)
    {
        // Requisitos de classes para cada guilda
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

                if ($neededPlayers->count() < $min) {
                    \Log::warning("Guilda '{$guild->name}' não tem {$min} jogadores da classe {$class}. Jogadores restantes não atendem ao requisito.");
                }

                // Atribui os jogadores necessários à guilda
                $guild->users()->attach($neededPlayers->pluck('id'));
                $players = $players->diff($neededPlayers);
            }
        }
    }

    private function distributeXP($guilds)
    {
        // Ordenar as guildas com base no XP total
        $sortedGuilds = $guilds->sortBy('total_xp')->values(); // Mantém as guildas ordenadas por XP total

        while (true) {
            $minGuild = $sortedGuilds->first();
            $maxGuild = $sortedGuilds->last();

            // Interrompe se a diferença de XP entre as guildas estiver equilibrada.
            if (($maxGuild->total_xp - $minGuild->total_xp) <= 10) {
                break;
            }

            // Move o jogador da guilda com mais XP para a guilda com menos XP
            $playerToMove = $maxGuild->users->sortByDesc('xp')->first();

            if (!$playerToMove) {
                break; // Evita loop infinito caso não haja jogadores elegíveis
            }

            // Move o jogador da guilda com mais XP para a guilda com menos XP
            $maxGuild->users()->detach($playerToMove->id);
            $minGuild->users()->attach($playerToMove->id);

            // Atualiza os valores de XP total das guildas
            $maxGuild->total_xp = $maxGuild->users->sum('xp');
            $minGuild->total_xp = $minGuild->users->sum('xp');

            // Reordena as guildas para a próxima iteração
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

        // Rebalancear guilda se necessário
        if ($guild->users()->count() > $maxPlayers) {
            $this->balanceGuilds();
        }

        return $guild;
    }
}
