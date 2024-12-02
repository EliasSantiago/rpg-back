<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RpgClassesService;
use Illuminate\Http\Request;
use App\Helpers\SetsJsonResponse;

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
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rpg_classes,name',
        ]);

        try {
            $rpgClass = $this->rpgClassService->store($validated);
            return $this->setJsonResponse($rpgClass, 201);
        } catch (\Exception $e) {
            return $this->setJsonResponse([
                'message' => $e->getMessage(),
                'error'   => true
            ], $e->getCode() ?? 500);
        }
    }
}
