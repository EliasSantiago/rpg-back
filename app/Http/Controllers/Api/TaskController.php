<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\TaskNotFoundException;
use App\Helpers\SetsJsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTask;
use App\Http\Requests\UpdateTask;
use App\Models\Task;
use App\Services\TaskService;

class TaskController extends Controller
{
    use SetsJsonResponse;

    protected $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tasks",
     *     summary="Listar tarefas",
     *     description="Obtém uma lista de tarefas.",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tarefas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", format="int32", example=5),
     *                     @OA\Property(property="user_id", type="integer", format="int32", example=1),
     *                     @OA\Property(property="title", type="string", example="Planejamento de Tarefas da Sprint 25"),
     *                     @OA\Property(property="description", type="string", example="Planejamento de Tarefas da Sprint 25"),
     *                     @OA\Property(property="status", type="string", example="pendente"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2023-09-11T21:20:28.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-09-11T21:20:28.000000Z"),
     *                 )
     *             ),
     *             @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/v1/tasks?page=1"),
     *             @OA\Property(property="from", type="integer", format="int32", example=1),
     *             @OA\Property(property="last_page", type="integer", format="int32", example=1),
     *             @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/v1/tasks?page=1"),
     *             @OA\Property(property="links", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="url", type="string", example=null),
     *                     @OA\Property(property="label", type="string", example="« Previous"),
     *                     @OA\Property(property="active", type="boolean", example=false),
     *                 ),
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="url", type="string", example="http://localhost:8000/api/v1/tasks?page=1"),
     *                     @OA\Property(property="label", type="string", example="1"),
     *                     @OA\Property(property="active", type="boolean", example=true),
     *                 ),
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="url", type="string", example=null),
     *                     @OA\Property(property="label", type="string", example="Next »"),
     *                     @OA\Property(property="active", type="boolean", example=false),
     *                 ),
     *             ),
     *             @OA\Property(property="next_page_url", type="string", example=null),
     *             @OA\Property(property="path", type="string", example="http://localhost:8000/api/v1/tasks"),
     *             @OA\Property(property="per_page", type="integer", format="int32", example=20),
     *             @OA\Property(property="prev_page_url", type="string", example=null),
     *             @OA\Property(property="to", type="integer", format="int32", example=3),
     *             @OA\Property(property="total", type="integer", format="int32", example=3),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefas não encontradas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tarefas não encontradas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Não autorizado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Erro interno do servidor.")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $tasks = $this->service->index();
            return $this->setJsonResponse($tasks, 200);
        } catch (TaskNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tasks",
     *     summary="Cria uma nova tarefa.",
     *     security={{"bearerAuth":{}}},
     *     description="Cria uma nova tarefa na aplicação.",
     *     tags={"Tasks"},

     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Planejamento de Tarefas da Sprint 25"),
     *             @OA\Property(property="description", type="string", example="Planejamento de Tarefas da Sprint 25"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarefa criada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Planejamento de Tarefas da Sprint 25"),
     *             @OA\Property(property="description", type="string", example="Planejamento de Tarefas da Sprint 25"),
     *             @OA\Property(property="user_id", type="integer", format="int32", example=1),
     *             @OA\Property(property="status", type="string", example="pendente"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-09-11T21:20:32.000000Z"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-09-11T21:20:32.000000Z"),
     *             @OA\Property(property="id", type="integer", format="int32", example=7)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro de validação dos campos."),
     *             @OA\Property(property="errors", type="object", example={"title": {"O campo título é obrigatório."}})
     *         )
     *     )
     * )
     */
    public function store(StoreTask $request)
    {
        try {
            $validated = $request->validated();
            $task = $this->service->store($validated);
            return $this->setJsonResponse($task, 201);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tasks/{idTask}",
     *     summary="Atualizar uma tarefa existente.",
     *     security={{"bearerAuth":{}}},
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="idTask",
     *         in="path",
     *         required=true,
     *         description="ID da tarefa a ser atualizada.",
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="abc"),
     *             @OA\Property(property="description", type="string", example="teste123"),
     *             @OA\Property(property="status", type="string", example="concluída"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes atualizados da tarefa",
     *         @OA\JsonContent(
     *             @OA\Property(property="idTask", type="integer", example=10),
     *             @OA\Property(property="user_id", type="integer", example=4),
     *             @OA\Property(property="title", type="string", example="abc"),
     *             @OA\Property(property="description", type="string", example="teste123"),
     *             @OA\Property(property="status", type="string", example="concluída"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tarefa não encontrada"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="ID inválido ou dados de entrada inválidos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="ID inválido ou dados de entrada inválidos"),
     *         )
     *     )
     * )
     */
    public function show(string $idTask)
    {
        try {
            if (empty($idTask) || !is_numeric($idTask)) {
                $data = [
                    'message' => 'Invalid ID',
                    'error'   => 'true',
                ];
                return response($data, 422, ['Content-Type', 'application/json']);
            }

            $task = $this->service->show($idTask);
            return $this->setJsonResponse($task, 200);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tasks/{task}",
     *     summary="Atualizar uma tarefa existente.",
     *     description="Atualiza uma tarefa existente do usuário autenticado na aplicação.",
     *     tags={"Tasks"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTask")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Planejamento de Tarefas da Sprint 25"),
     *             @OA\Property(property="description", type="string", example="Planejamento de Tarefas da Sprint 25"),
     *             @OA\Property(property="status", type="string", example="concluída"),
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="task",
     *         in="path",
     *         description="ID da Task",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa atualizada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="idTask", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Nova descrição da tarefa"),
     *             @OA\Property(property="description", type="string", example="Nova descrição da tarefa"),
     *             @OA\Property(property="status", type="string", example="concluída"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tarefa não encontrada"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Erro de validação dos campos."),
     *             @OA\Property(property="errors", type="object", example={"title": {"O título é obrigatório."}})
     *         )
     *     )
     * )
     */
    public function update(UpdateTask $request, Task $task)
    {
        try {
            $validated = $request->validated();
            $task = $this->service->update($task->id, $validated);
            return $this->setJsonResponse($task, 200);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/v1/tasks/{task}",
     *     summary="Excluir uma tarefa existente",
     *     description="Exclui uma tarefa existente do usuário autenticado na aplicação.",
     *     tags={"Tasks"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *      @OA\Parameter(
     *         name="task",
     *         in="path",
     *         description="ID da Task",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Tarefa excluída com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tarefa não encontrada"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro ao excluir a tarefa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Erro ao excluir a tarefa"),
     *         )
     *     )
     * )
     */
    public function destroy(Task $task)
    {
        try {
            $this->service->destroy($task->id);
            return response()->noContent();
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }
}
