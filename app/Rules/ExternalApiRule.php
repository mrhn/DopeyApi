<?php

namespace App\Rules;

use App\Services\ApiService;
use Illuminate\Contracts\Validation\Rule;

class ExternalApiRule implements Rule
{
    /**
     * @var ApiService
     */
    protected $service;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return (bool) $this->service->get($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute is not valid';
    }
}
