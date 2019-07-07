<?php

namespace App\Http\Transformers;

use App\Models\Beer;
use App\Models\Meal;
use App\Models\Reservation;
use App\Services\BeerService;
use App\Services\MealService;
use League\Fractal\TransformerAbstract;

class ReservationTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'beers',
        'meals',
        'tables',
    ];

    public function transform(Reservation $reservation): array
    {
        return [
            'id' => $reservation->id,
            'time' => (string) $reservation->time,
        ];
    }

    public function includeBeers(Reservation $reservation)
    {
        $beerService = app(BeerService::class);

        return $this->collection($reservation->beers->map(function (Beer $beer) use ($beerService) {
            return $beerService->get($beer->external_id);
        }), new BeerTransformer());
    }

    public function includeMeals(Reservation $reservation)
    {
        $mealService = app(MealService::class);

        return $this->collection($reservation->meals->map(function (Meal $meal) use ($mealService) {
            return $mealService->get($meal->external_id);
        }), new MealTransformer());
    }

    public function includeTables(Reservation $reservation)
    {
        return $this->collection($reservation->tables, new TableTransformer());
    }
}
