<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidReservationTime implements Rule
{
    public const VALID_BOOKING_TIME = [16, 18, 20];

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
        $date = Carbon::parse($value);

        return \in_array($date->hour, static::VALID_BOOKING_TIME, true) && 0 === $date->minute && 0 === $date->second;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute is not a valid reservation time.';
    }
}
