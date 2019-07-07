<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function receiving_200_when_creating_a_reservation()
    {
        $user = factory(User::class)->create();

        $payload = [
            'time' => Carbon::now()->hour(16)->minute(0)->second(0),
            'seats' => 2,
            'beers' => [26, 26],
            'meals' => [52772, 52772],
        ];

        $response = $this->json('POST', "/api/users/{$user->email}/reservation", $payload);

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        'time' => 1,
                    ],
                ]
            )
        ;
    }
}
