<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GuildService;
use Illuminate\Http\Request;
use App\Helpers\SetsJsonResponse;

class GuildController extends Controller
{
    use SetsJsonResponse;

    protected $guildService;

    public function __construct(GuildService $guildService)
    {
        $this->guildService = $guildService;
    }

    public function index()
    {
        try {
            $guilds = $this->guildService->getAllGuilds();
            return $this->setJsonResponse($guilds, 200);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }

    public function show($guildId)
    {
        try {
            $guild = $this->guildService->show($guildId);
            return $this->setJsonResponse($guild, 200);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:guilds,name',
            'description' => 'nullable|string|max:1000',
            'max_players' => 'required|integer|min:1',
            'leader_id' => 'required|exists:users,id',
        ]);

        try {
            $guild = $this->guildService->store($validated);
            return $this->setJsonResponse($guild, 201);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }

    public function addUserToGuild(Request $request, $guildId)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $guild = $this->guildService->addUserToGuild($validated, $guildId);

            return $this->setJsonResponse([
                'message' => 'Usuário adicionado à guilda com sucesso.',
                'guild'   => $guild
            ], 200);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }

    public function balance()
    {
        try {
            $this->guildService->balanceGuilds();

            return $this->setJsonResponse([
                'message' => 'Guildas balanceadas com sucesso.',
            ], 200);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }

    public function updateMaxPlayers(Request $request, $guildId)
    {
        $validated = $request->validate([
            'max_players' => 'required|integer|min:1',
        ]);

        try {
            $guild = $this->guildService->updateMaxPlayers($guildId, $validated['max_players']);
            return $this->setJsonResponse([
                'message' => 'Limite de jogadores atualizado com sucesso.',
                'guild'   => $guild,
            ], 200);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }
}
