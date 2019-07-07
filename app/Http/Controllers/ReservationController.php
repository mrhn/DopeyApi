<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReservationRequest;
use App\Http\Transformers\ReservationTransformer;
use App\Models\User;
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

    public function all(string $email): JsonResponse
    {
        /** @var User $user */
        $user = User::where('email', $email)->firstOrFail();
        $reservations = $this->service->all($user);

        return $this->response($reservations);
    }

    public function get(string $email, int $id): JsonResponse
    {
        /** @var User $user */
        $user = User::where('email', $email)->firstOrFail();
        $reservations = $this->service->get($user, $id);

        return $this->response($reservations);
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
