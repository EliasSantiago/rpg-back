<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UserNotInGuildException;
use App\Http\Controllers\Controller;
use App\Services\GuildService;
use Illuminate\Http\Request;
use App\Helpers\SetsJsonResponse;
use App\Http\Requests\AddUserToGuildRequest;
use App\Http\Requests\CreateGuildRequest;
use App\Http\Requests\RemoveUserFromGuildRequest;
use App\Http\Requests\UpdateGuildRequest;
use App\Http\Requests\UpdateMaxPlayersRequest;
use Illuminate\Http\Response;

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
            $statusCode = is_int($e->getCode()) && $e->getCode() > 0 ? $e->getCode() : 500;
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $statusCode);
        }
    }

    public function show($guildId)
    {
        try {
            $guild = $this->guildService->show($guildId);
            return $this->setJsonResponse($guild, 200);
        } catch (\Exception $e) {
            $statusCode = is_int($e->getCode()) && $e->getCode() > 0 ? $e->getCode() : 500;
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $statusCode);
        }
    }

    public function store(CreateGuildRequest $request)
    {
        try {
            $validated = $request->validated();
            $guild = $this->guildService->store($validated);
            return $this->setJsonResponse($guild, 201);
        } catch (\Exception $e) {
            $statusCode = is_int($e->getCode()) && $e->getCode() > 0 ? $e->getCode() : 500;
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $statusCode);
        }
    }

    public function update(UpdateGuildRequest $request, $guildId)
    {
        try {
            $validated = $request->validated();
            $guild = $this->guildService->update($validated, $guildId);
            return $this->setJsonResponse($guild, 201);
        } catch (\Exception $e) {
            $statusCode = is_int($e->getCode()) && $e->getCode() > 0 ? $e->getCode() : 500;
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $statusCode);
        }
    }

    public function delete($guildId)
    {
        try {
            $this->guildService->delete($guildId);
            return $this->setJsonResponse([
                'message' => 'Guilda deletada com sucesso.'
            ], 201);
        } catch (\Exception $e) {
            $statusCode = is_int($e->getCode()) && $e->getCode() > 0 ? $e->getCode() : 500;
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $statusCode);
        }
    }

    public function addUserToGuild(AddUserToGuildRequest $request, $guildId)
    {
        $validated = $request->validated();

        try {
            $guild = $this->guildService->addUserToGuild($validated, $guildId);

            return $this->setJsonResponse([
                'message' => 'Usuário adicionado à guilda com sucesso.',
                'guild'   => $guild
            ], 200);
        } catch (\Exception $e) {
            $statusCode = is_int($e->getCode()) && $e->getCode() > 0 ? $e->getCode() : 500;
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $statusCode);
        }
    }

    public function removeUserFromGuild(RemoveUserFromGuildRequest $request, $guildId, $userId)
    {
        $validated = $request->validated();

        try {
            $guild = $this->guildService->removeUserFromGuild($userId, $guildId);

            return $this->setJsonResponse([
                'message' => 'Usuário removido com sucesso.',
                'guild'   => $guild
            ], Response::HTTP_OK);
        } catch (UserNotInGuildException $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], 404);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => 'Erro inesperado: ' . $e->getMessage(),
                'error'   => true
            ], 500);
        }
    }

    public function balance()
    {
        try {
            $res = $this->guildService->balanceGuilds();

            if (isset($res) && count($res) > 0) {
                $message = "Guildas balanceadas com sucesso. Algumas delas não atendem aos requisitos de classes, adicione mais jogadores e refaça o balanceamento.";
            } else {
                $message = 'Guildas balanceadas com sucesso.';
            }

            return $this->setJsonResponse([
                'message' => $message
            ], 200);
        } catch (\Exception $e) {
            $statusCode = is_int($e->getCode()) && $e->getCode() > 0 ? $e->getCode() : 500;
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $statusCode);
        }
    }


    public function updateMaxPlayers(UpdateMaxPlayersRequest $request, $guildId)
    {
        try {
            $guild = $this->guildService->updateMaxPlayers($guildId, $request->validated()['max_players']);

            return $this->setJsonResponse([
                'message' => 'Limite de jogadores atualizado com sucesso.',
                'guild'   => $guild,
            ], 200);
        } catch (\Exception $e) {
            $statusCode = is_int($e->getCode()) && $e->getCode() > 0 ? $e->getCode() : 500;

            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true,
            ], $statusCode);
        }
    }
}
