<?php

namespace App\Http\Requests;

use App\Rules\ValidBeer;
use App\Rules\ValidMeal;
use App\Rules\ValidReservationTime;
use App\Rules\ValidUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

class CreateReservationRequest extends FormRequest
{
    public const VALID_SEATS = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $seats = $this->json->getInt('seats', null);

        return [
            'time' => ['required', 'date', new ValidReservationTime()],
            'user' => ['required', 'email', new ValidUser()],
            'seats' => ['required', 'number', new In(static::VALID_SEATS)],
            'beers' => ['required', 'array', $seats ? "size:{$seats}" : ''],
            'beers.*' => [app(ValidBeer::class)],
            'meals' => ['required', 'array', $seats ? "size:{$seats}" : ''],
            'meals.*' => [app(ValidMeal::class)],
        ];
    }
}
