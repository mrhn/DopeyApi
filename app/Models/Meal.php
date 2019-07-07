<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Meal.
 *
 * @method static Model firstOrCreate($data)
 */
class Meal extends Model
{
    protected $fillable = [
        'external_id',
    ];
}
