<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SetsJsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmedRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use SetsJsonResponse;

    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $users = $this->service->index();
            return $this->setJsonResponse($users, 200);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error' => true
            ], $e->getCode() ?: 500);
        }
    }

    public function update(UpdateUserRequest $request, $userId)
    {
        try {
            $validated = $request->validated();

            $user = $this->service->update($validated, $userId);
            return $this->setJsonResponse([
                'message' => 'Jogador confirmado com sucesso.',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function changeConfirmationAll(ConfirmedRequest $request)
    {
        try {
            $validated = $request->validated();

            $this->service->changeConfirmationAll($validated);
            $message = $validated['confirmed']
                ? 'Jogadores confirmados com sucesso.'
                : 'Jogadores desconfirmados com sucesso.';

            return $this->setJsonResponse([
                'message' => $message,
            ], 200);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function show($userId)
    {
        try {
            $user = $this->service->show($userId);
            return $this->setJsonResponse([
                $user
            ]);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function delete($userId)
    {
        try {
            $this->service->delete($userId);
            return $this->setJsonResponse([
                'message' => 'Usuário deletado com sucesso'
            ]);
        } catch (\Exception $e) {
            dd($e);
            return $this->setJsonResponse([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }
}
