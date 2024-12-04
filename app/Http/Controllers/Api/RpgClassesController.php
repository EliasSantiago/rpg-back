<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RpgClassesService;
use Illuminate\Http\Request;
use App\Helpers\SetsJsonResponse;
use App\Http\Requests\StoreRpgClassRequest;

class RpgClassesController extends Controller
{
    use SetsJsonResponse;

    protected $rpgClassService;

    public function __construct(RpgClassesService $rpgClassService)
    {
        $this->rpgClassService = $rpgClassService;
    }

    public function index()
    {
        try {
            $classes = $this->rpgClassService->getAllClasses();
            return $this->setJsonResponse($classes, 200);
        } catch (\Exception $e) {
            $statusCode = is_int($e->getCode()) && $e->getCode() > 0 ? $e->getCode() : 500;
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $statusCode);
        }
    }

    public function store(StoreRpgClassRequest $request)
    {
        try {
            $validated = $request->validated();
            $rpgClass = $this->rpgClassService->store($validated);
            return $this->setJsonResponse($rpgClass, 201);
        } catch (\Exception $e) {
            $statusCode = is_int($e->getCode()) && $e->getCode() > 0 ? $e->getCode() : 500;
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $statusCode);
        }
    }
}
