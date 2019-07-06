<?php

namespace Tests\Unit\Services;

use App\Models\DTO\Beer;
use App\Services\BeerService;
use Illuminate\Support\Arr;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class BeerServiceTest extends TestCase
{
    /**
     * @var BeerService
     */
    protected $beerService;

    /**
     * @var Beer
     */
    protected $buzzBeer;

    /**
     * @var Beer
     */
    protected $skullCandy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->beerService = app(BeerService::class);

        $this->buzzBeer = new Beer(
            1,
            'Buzz',
            'A light, crisp and bitter IPA brewed with English and American hops. A small batch brewed only once.',
            4.5
        );

        $this->skullCandy = new Beer(
            26,
            'Skull Candy',
            'The first beer that we brewed on our newly commissioned 5000 litre brewhouse in Fraserburgh 2009. A beer with the malt and body of an English bitter, but the heart and soul of vibrant citrus US hops.',
            3.5
        );
    }

    /** @test */
    public function can_retrieve_bears()
    {
        $beers = $this->beerService->all();

        static::assertCount(25, $beers);

        static::assertEquals($this->buzzBeer, Arr::first($beers));
    }

    /** @test */
    public function can_search_bears()
    {
        $beers = $this->beerService->all('Skull');

        static::assertCount(1, $beers);

        static::assertEquals($this->skullCandy, Arr::first($beers));
    }

    /** @test */
    public function can_find_single_beer()
    {
        $beer = $this->beerService->get(26);

        static::assertEquals($this->skullCandy, $beer);
    }
}
