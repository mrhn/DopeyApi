<?php

namespace Tests\Feature;

use App\Models\Beer;
use App\Models\Meal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use DatabaseSeeder;

/**
 * @internal
 * @coversNothing
 */
final class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    /** @test */
    public function receiving_200_when_creating_a_reservation()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(16);

        $payload = [
            'time' => $date,
            'seats' => 2,
            'beers' => [26, 26],
            'meals' => [52772, 52772],
        ];

        $response = $this->json('POST', "/api/users/{$user->email}/reservations", $payload);

        $response->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJson(
                [
                    'data' => [
                        'time' => $date->format('Y-m-d H:i:s'),
                    ],
                ]
            )
        ;

        $this->assertDatabaseHas('reservations', ['time' => $date]);

        $this->assertDatabaseHas('reservation_table', ['reservation_id' => $response->json()['data']['id']]);

        $this->assertDatabaseHas('meals', ['external_id' => 52772]);

        $this->assertDatabaseHas('meal_reservation', ['meal_id' => Meal::where('external_id', 52772)->first()->id]);

        $this->assertDatabaseHas('beers', ['external_id' => 26]);

        $this->assertDatabaseHas('beer_reservation', ['beer_id' => Beer::where('external_id', 26)->first()->id]);

    }
}
