<?php

namespace Tests\Unit\Services;

use App\Models\DTO\Meal;
use App\Services\MealService;
use Illuminate\Support\Arr;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class MealServiceTest extends TestCase
{
    /**
     * @var MealService
     */
    protected $mealService;

    /**
     * @var Meal
     */
    protected $dal;

    /**
     * @var Meal
     */
    protected $chicken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mealService = app(MealService::class);

        $this->dal = new Meal(
            52785,
            'Dal fry',
            'Indian'
        );

        $this->chicken = new Meal(
            52772,
            'Teriyaki Chicken Casserole',
            'Japanese'
        );
    }

    /** @test */
    public function can_retrieve_meals()
    {
        $meals = $this->mealService->all();

        static::assertCount(25, $meals);

        static::assertEquals($this->dal, Arr::first($meals));
    }

    /** @test */
    public function can_search_meals()
    {
        $meals = $this->mealService->all('Teriyaki Chicken');

        static::assertCount(1, $meals);

        static::assertEquals($this->chicken, Arr::first($meals));
    }

    /** @test */
    public function can_find_single_meal()
    {
        $meal = $this->mealService->get(52772);

        static::assertEquals($this->chicken, $meal);
    }
}
