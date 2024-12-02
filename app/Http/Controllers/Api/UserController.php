<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SetsJsonResponse;
use App\Http\Controllers\Controller;
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

    public function update(Request $request, $userId)
    {
        try {
            $validated = $request->validate([
                'confirmed' => 'required|boolean'
            ]);

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

    public function confirmAll(Request $request)
    {
        try {
            $validated = $request->validate([
                'confirmed' => 'required|boolean'
            ]);

            $this->service->confirmAll($validated);
            return $this->setJsonResponse([
                'message' => 'Jogadores confirmados com sucesso.',
            ], 200);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }
}
