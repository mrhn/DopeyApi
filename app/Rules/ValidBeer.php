<?php

namespace App\Rules;

use App\Services\BeerService;
use Illuminate\Contracts\Validation\Rule;

class ValidBeer extends ExternalApiRule implements Rule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(BeerService $beerService)
    {
        $this->service = $beerService;
    }
}
