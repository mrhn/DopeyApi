<?php

namespace App\Http\Controllers;

use App\Http\Transformers\MealTransformer;
use App\Services\MealService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * @var MealService
     */
    protected $service;

    public function __construct(MealService $service)
    {
        $this->service = $service;

        $this->transformer = new MealTransformer();
    }

    public function all(Request $request): JsonResponse
    {
        return $this->response($this->service->all($request->query->get('search', '')));
    }

    public function get(Request $request, int $id): JsonResponse
    {
        return $this->response($this->service->get($id));
    }
}
