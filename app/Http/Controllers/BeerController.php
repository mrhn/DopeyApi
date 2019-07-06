<?php

namespace App\Http\Controllers;

use App\Http\Transformers\BeerTransformer;
use App\Services\BeerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BeerController extends Controller
{
    /**
     * @var BeerService
     */
    protected $service;

    public function __construct(BeerService $service)
    {
        $this->service = $service;

        $this->transformer = new BeerTransformer();
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
