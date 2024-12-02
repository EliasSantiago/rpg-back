<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SetsJsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUser;
use App\Http\Requests\RegisterUser;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;

class AuthController extends Controller
{
    use SetsJsonResponse;

    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="Registrar um novo usuário.",
     *     description="Registra um novo usuário na aplicação.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="secret"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registro bem-sucedido",
     *         @OA\JsonContent(
     *             type="object",
     *                 @OA\Property(property="name", type="string", example="Elias Fonseca"),
     *                 @OA\Property(property="email", type="string", example="contato3@ignitor.com.br"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-09-11T21:04:55.000000Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-09-11T21:04:55.000000Z"),
     *                 @OA\Property(property="id", type="integer", format="int32", example=3),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Erro de validação dos campos."),
     *             @OA\Property(property="errors", type="object", example={"email": {"O email é obrigatório."}})
     *         )
     *     )
     * )
     */
    public function register(RegisterUser $request)
    {
        try {            
            $validated = $request->validated();
            $validated['password'] = bcrypt($validated['password']);
            $user = $this->service->register($validated);

            return $this->setJsonResponse($user, 201);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?: 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Realizar login do usuário.",
     *     description="Realiza o login do usuário na aplicação.",
     *     tags={"Auth"},

     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             type="object",
     *                 @OA\Property(property="id", type="integer", format="int32", example=1),
     *                 @OA\Property(property="name", type="string", example="Elias Fonseca"),
     *                 @OA\Property(property="email", type="string", example="contato@ignitor.com.br"),
     *                 @OA\Property(property="email_verified_at", type="string", format="date-time", example=null),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2023-09-11T20:21:39.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2023-09-11T20:21:39.000000Z"),
     *                 @OA\Property(property="token_type", type="string", example="Bearer"),
     *                 @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Unauthorized.")
     *         )
     *     )
     * )
     */
    public function login(LoginUser $request)
    {
        try {
            $data = $request->validated();
            $user = $this->service->login($data);
            return $this->setJsonResponse($user);
        } catch (AuthenticationException $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], 401);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/me",
     *     summary="Obter informações do usuário logado.",
     *     description="Obtém informações do usuário atualmente logado na aplicação.",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Informações do usuário",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", format="int32", example=1),
     *             @OA\Property(property="name", type="string", example="Elias Fonseca"),
     *             @OA\Property(property="email", type="string", format="email", example="contato@ignitor.com.br"),
     *             @OA\Property(property="email_verified_at", type="string", format="date-time", example=null),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-09-11T20:21:39.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-09-11T20:21:39.000000Z"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Não autorizado.")
     *         )
     *     )
     * )
     */
    public function information()
    {
        try {
            return $this->setJsonResponse($this->service->information());
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }
}
