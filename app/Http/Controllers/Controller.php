<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use League\Fractal\TransformerAbstract;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * @var TransformerAbstract
     */
    protected $transformer;

    protected function response($data, int $code = JsonResponse::HTTP_OK): JsonResponse
    {
        return fractal($data, $this->transformer)->respond($code);
    }
}
