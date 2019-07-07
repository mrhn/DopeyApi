<?php

namespace App;

use App\Models\DTO\Beer;
use App\Models\DTO\Meal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends Model
{
    protected $fillable = [
        'fillable',
    ];

    protected $dates = [
        'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tables(): BelongsToMany
    {
        return $this->belongsToMany(Table::class);
    }

    public function beers(): BelongsToMany
    {
        return $this->belongsToMany(Beer::class);
    }

    public function meals(): BelongsToMany
    {
        return $this->belongsToMany(Meal::class);
    }

    protected function saveBeers(array $beers, $reservation): void
    {
        foreach ($beers as $beer) {
            $beer = Beer::firstOrCreate(['external_id' => $beer]);

            $this->beers()->save($beer);
        }
    }

    protected function saveMeals(array $meals, $reservation): void
    {
        foreach ($meals as $meal) {
            $meal = Meal::firstOrCreate(['external_id' => $meal]);

            $this->beers()->save($meal);
        }
    }
}
