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
final class ReservationCreateTest extends TestCase
{
    use RefreshDatabase;

    protected $beerId = 26;

    protected $mealId = 52772;

    protected function setUp(): void
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
            'beers' => [$this->beerId, $this->beerId],
            'meals' => [$this->mealId, $this->mealId],
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
        $this->assertDatabaseCount('reservation_table', 1);

        $this->assertDatabaseHas('meals', ['external_id' => $this->mealId]);

        $this->assertDatabaseHas('meal_reservation', ['meal_id' => Meal::where('external_id', $this->mealId)->first()->id]);
        $this->assertDatabaseCount('meal_reservation', 2);

        $this->assertDatabaseHas('beers', ['external_id' => $this->beerId]);

        $this->assertDatabaseHas('beer_reservation', ['beer_id' => Beer::where('external_id', $this->beerId)->first()->id]);
        $this->assertDatabaseCount('beer_reservation', 2);
    }

    /** @test */
    public function receiving_200_can_reserve_multiple_tables()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(18);

        $payload = [
            'time' => $date,
            'seats' => 10,
            'beers' => [$this->beerId, $this->beerId, $this->beerId, $this->beerId, $this->beerId, $this->beerId, $this->beerId, $this->beerId, $this->beerId, $this->beerId],
            'meals' => [$this->mealId, $this->mealId, $this->mealId, $this->mealId, $this->mealId, $this->mealId, $this->mealId, $this->mealId, $this->mealId, $this->mealId],
        ];

        $response = $this->json('POST', "/api/users/{$user->email}/reservations", $payload);

        $response->assertStatus(JsonResponse::HTTP_CREATED);

        $this->assertDatabaseCount('reservation_table', 5);
    }

    /** @test */
    public function receiving_422_can_not_reserve_more_than_10_seats()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(16);

        $payload = [
            'time' => $date,
            'seats' => 11,
            'beers' => [$this->beerId, $this->beerId, $this->beerId, $this->beerId, $this->beerId, $this->beerId, $this->beerId, $this->beerId, $this->beerId, $this->beerId, $this->beerId],
            'meals' => [$this->mealId, $this->mealId, $this->mealId, $this->mealId, $this->mealId, $this->mealId, $this->mealId, $this->mealId, $this->mealId, $this->mealId, $this->mealId],
        ];

        $response = $this->json('POST', "/api/users/{$user->email}/reservations", $payload);

        $response
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => ['seats' => ['The selected seats is invalid.']],
            ])
        ;
    }

    /** @test */
    public function receiving_409_can_not_reserve_if_all_table_are_filled()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(16);

        Table::all()->each(function (Table $table) use ($date, $user) {
            $reservation = new Reservation(['time' => $date]);
            $reservation->user()->associate($user);
            $table->reservations()->save($reservation);
        });

        $payload = [
            'time' => $date,
            'seats' => 2,
            'beers' => [$this->beerId, $this->beerId],
            'meals' => [$this->mealId, $this->mealId],
        ];

        $response = $this->json('POST', "/api/users/{$user->email}/reservations", $payload);

        $response
            ->assertStatus(JsonResponse::HTTP_CONFLICT)
            ->assertJson([
                'message' => NoTablesException::ERROR_MESSAGE,
            ])
        ;
    }

    /** @test */
    public function receiving_200_can_book_different_time_while_other_time_filled_up()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(16);

        Table::all()->each(function (Table $table) use ($date, $user) {
            $reservation = new Reservation(['time' => $date]);
            $reservation->user()->associate($user);
            $table->reservations()->save($reservation);
        });

        $newDate = Carbon::now()->startOfDay()->hour(18);

        $payload = [
            'time' => $newDate,
            'seats' => 2,
            'beers' => [$this->beerId, $this->beerId],
            'meals' => [$this->mealId, $this->mealId],
        ];

        $response = $this->json('POST', "/api/users/{$user->email}/reservations", $payload);

        $response
            ->assertStatus(JsonResponse::HTTP_OK)
        ;
    }

    /** @test */
    public function receiving_422_can_not_reserve_if_no_beers()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(16);

        $payload = [
            'time' => $date,
            'seats' => 2,
            'beers' => [],
            'meals' => [$this->mealId, $this->mealId],
        ];

        $response = $this->json('POST', "/api/users/{$user->email}/reservations", $payload);

        $response
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => ['beers' => ['The beers field is required.']],
            ])
        ;
    }

    /** @test */
    public function receiving_404_if_beer_id_is_invalid()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(16);

        $payload = [
            'time' => $date,
            'seats' => 2,
            'beers' => [-1],
            'meals' => [$this->mealId, $this->mealId],
        ];

        $response = $this->json('POST', "/api/users/{$user->email}/reservations", $payload);

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }

    /** @test */
    public function receiving_200_can_not_reserve_if_no_meals()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(16);

        $payload = [
            'time' => $date,
            'seats' => 2,
            'beers' => [$this->beerId, $this->beerId],
            'meals' => [],
        ];

        $response = $this->json('POST', "/api/users/{$user->email}/reservations", $payload);

        $response
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => ['meals' => ['The meals field is required.']],
            ])
        ;
    }

    /** @test */
    public function receiving_404_if_meal_id_is_invalid()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(16);

        $payload = [
            'time' => $date,
            'seats' => 2,
            'beers' => [$this->beerId, $this->beerId],
            'meals' => [-1],
        ];

        $response = $this->json('POST', "/api/users/{$user->email}/reservations", $payload);

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }

    /** @test */
    public function receiving_404_if_no_user()
    {
        $date = Carbon::now()->startOfDay()->hour(16);

        $payload = [
            'time' => $date,
            'seats' => 1,
            'beers' => [$this->beerId],
            'meals' => [$this->mealId],
        ];

        $response = $this->json('POST', '/api/users/random/reservations', $payload);

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }

    /** @test */
    public function receiving_422_if_incorrect_amount_of_beer_or_meals()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(16);

        $payload = [
            'time' => $date,
            'seats' => 2,
            'beers' => [$this->beerId],
            'meals' => [$this->mealId, $this->mealId, $this->mealId],
        ];

        $response = $this->json('POST', "/api/users/{$user->email}/reservations", $payload);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => ['meals' => ['The meals must contain 2 items.'], 'beers' => ['The beers must contain 2 items.']],
            ])
        ;
    }

    /** @test */
    public function receiving_422_if_incorrect_time()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(16)->minute(30);

        $payload = [
            'time' => $date,
            'seats' => 1,
            'beers' => [$this->beerId],
            'meals' => [$this->mealId],
        ];

        $response = $this->json('POST', "/api/users/{$user->email}/reservations", $payload);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => ['time' => ['The time is not a valid reservation time.']], ])
        ;
    }

    /** @test */
    public function receiving_422_if_incorrect_time_outside_opening_hours()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(10)->minute(00);

        $payload = [
            'time' => $date,
            'seats' => 1,
            'beers' => [$this->beerId],
            'meals' => [$this->mealId],
        ];

        $response = $this->json('POST', "/api/users/{$user->email}/reservations", $payload);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => ['time' => ['The time is not a valid reservation time.']], ])
        ;
    }

    /** @test */
    public function receiving_200_if_booking_last_time()
    {
        $user = factory(User::class)->create();

        $date = Carbon::now()->startOfDay()->hour(20);

        $payload = [
            'time' => $date,
            'seats' => 1,
            'beers' => [$this->beerId],
            'meals' => [$this->mealId],
        ];

        $response = $this->json('POST', "/api/users/{$user->email}/reservations", $payload);

        $response->assertStatus(JsonResponse::HTTP_CREATED);
    }
}
