<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class MealTest extends TestCase
{
    /** @test */
    public function receiving_200_when_getting_all_meals()
    {
        $response = $this->get('/api/meals');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'type',
                    ]
                ],
            ]);
    }

    /** @test */
    public function receiving_200_when_getting_single_meal()
    {
        $response = $this->get('/api/meals/52772');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'type',
                ],
            ]);
    }
}
