<?php
namespace App\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateTask",
 *     @OA\Property(property="title", type="string", example="Task 1"),
 *     @OA\Property(property="description", type="string", example="Description 1"),
 *     @OA\Property(property="status", type="string", example="concluída"),
 * )
 */
class UpdateTask{}