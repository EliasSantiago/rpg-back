<?php
namespace App\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Task",
 *     @OA\Property(property="id", type="integer", example="1"),
 *     @OA\Property(property="title", type="string", example="Task 1"),
 *     @OA\Property(property="description", type="string", example="Description 1"),
 *     @OA\Property(property="status", type="string", example="concluída"),
 *     @OA\Property(property="created_at", type="string", example="2022-01-01 00:00:00"),
 *     @OA\Property(property="updated_at", type="string", example="2022-01-01 00:00:00"),
 * )
 */
class TaskSchema{}