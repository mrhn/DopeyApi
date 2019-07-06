<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class BeerTest extends TestCase
{
    /** @test */
    public function receiving_200_when_getting_all_beers()
    {
        $response = $this->get('/api/beers');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'description',
                        'abv',
                    ],
                ],
            ])
        ;
    }

    /** @test */
    public function receiving_200_when_getting_single_beer()
    {
        $response = $this->get('/api/beers/26');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'abv',
                ],
            ])
        ;
    }
}
