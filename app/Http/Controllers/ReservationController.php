<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReservationRequest;
use App\Http\Transformers\ReservationTransformer;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    /**
     * @var ReservationService
     */
    protected $service;

    public function __construct(ReservationService $reservationService)
    {
        $this->service = $reservationService;
        $this->transformer = new ReservationTransformer();
    }

    public function all(): JsonResponse
    {
    }

    public function get(): JsonResponse
    {
    }

    public function create(CreateReservationRequest $request, string $email): JsonResponse
    {
        $json = $request->validated();

        $reservation = $this->service->create(
            $request->user,
            Carbon::parse($json['time']),
            $json['seats'],
            $json['beers'],
            $json['meals']
        );

        return $this->response($reservation, JsonResponse::HTTP_CREATED);
    }
}
