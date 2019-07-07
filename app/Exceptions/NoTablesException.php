<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class NoTablesException extends HttpResponseException
{
    const ERROR_MESSAGE = 'No more tables available';

    public function __construct()
    {
        $response = new JsonResponse(['message' => static::ERROR_MESSAGE], JsonResponse::HTTP_CONFLICT);
        parent::__construct($response);
    }
}
