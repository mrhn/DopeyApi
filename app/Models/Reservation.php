<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Reservation
 * @package App\Models
 *
 * @property int $id
 * @property Carbon $time
 */
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

    public function saveBeers(array $beers): void
    {
        foreach ($beers as $beer) {
            $beer = Beer::firstOrCreate(['external_id' => $beer]);

            $this->beers()->save($beer);
        }
    }

    public function saveMeals(array $meals): void
    {
        foreach ($meals as $meal) {
            $meal = Meal::firstOrCreate(['external_id' => $meal]);

            $this->beers()->save($meal);
        }
    }
}
