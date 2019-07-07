<?php

namespace Tests\Feature;

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
final class ReservationAllTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    /** @test */
    public function receiving_200_when_getting_all_reservations_for_user()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(16);

        Table::all()->take(3)->each(function (Table $table) use ($date, $user) {
            $reservation = new Reservation(['time' => $date]);
            $reservation->user()->associate($user);
            $table->reservations()->save($reservation);
            $reservation->saveBeers([26]);
            $reservation->saveMeals([52772]);
        });

        $secondDate = Carbon::now()->startOfDay()->hour(16);
        $secondUser = factory(User::class)->create();

        // should not be shown
        Table::all()->take(3)->each(function (Table $table) use ($secondDate, $secondUser) {
            $reservation = new Reservation(['time' => $secondDate]);
            $reservation->user()->associate($secondUser);
            $table->reservations()->save($reservation);
            $reservation->saveBeers([26]);
            $reservation->saveMeals([52772]);
        });

        $response = $this->json('GET', "/api/users/{$user->email}/reservations?include=meals,beers,tables");

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonCount(3, 'data')
            ->assertJson(
                [
                    'data' => [
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
                                ],
                            ]],
                        ],
                    ],
                ]
            )
        ;
    }

    /** @test */
    public function receiving_404_when_providing_invalid_email()
    {
        $response = $this->json('GET', '/api/users/random/reservations?include=meals,beers,tables');

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }
}
