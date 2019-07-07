<?php

namespace Tests\Feature;

use App\Exceptions\NoTablesException;
use App\Models\Beer;
use App\Models\Meal;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ReservationGetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    /** @test */
    public function receiving_200_when_getting_single_reservation_for_user()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(16);

        $reservation = new Reservation(['time' => $date]);
        $reservation->user()->associate($user);
        Table::all()->first()->reservations()->save($reservation);
        $reservation->saveBeers([26]);
        $reservation->saveMeals([52772]);

        $response = $this->json('GET', "/api/users/{$user->email}/reservations/$reservation->id?include=beers,meals,tables");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson(
                [
                    'data' =>
                        [

                            'time' => $date->format('Y-m-d H:i:s'),
                            'beers' => ['data' => [[
                                'id' => 26,
                            ]]],
                            'meals' => ['data' => [[
                                'id' => 52772,
                            ]]],
                            'tables' => ['data' => [
                                [
                                    'seats' => 2,
                                ]
                            ]],
                        ],
                ]
            );
    }

    /** @test */
    public function receiving_404_when_providing_invalid_email()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(16);

        $reservation = new Reservation(['time' => $date]);
        $reservation->user()->associate($user);
        Table::all()->first()->reservations()->save($reservation);
        $reservation->saveBeers([26]);
        $reservation->saveMeals([52772]);

        $response = $this->json('GET', "/api/users/random/reservations/$reservation->id?include=beers,meals,tables");

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
