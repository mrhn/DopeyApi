<?php

namespace App\Rules;

use App\Services\MealService;
use Illuminate\Contracts\Validation\Rule;

class ValidMeal extends ExternalApiRule implements Rule
{
    /**
     * @var MealService
     */
    protected $service;

    /**
     * Create a new rule instance.
     */
    public function __construct(MealService $mealService)
    {
        $this->service = $mealService;
    }
}
