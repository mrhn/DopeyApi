<?php

namespace App\Http\Requests;

use App\Rules\ValidBeer;
use App\Rules\ValidMeal;
use App\Rules\ValidReservationTime;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

class CreateReservationRequest extends FormRequest
{
    public const VALID_SEATS = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

    /**
     * @var User
     */
    public $user;

    public function authorize()
    {
        /** @var User $user */
        $user = User::where('email', $this->input('email'))->firstOrFail();
        $this->user = $user;

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $seats = null;

        if ($this->json) {
            $seats = $this->json->getInt('seats');
        }

        return [
            'time' => ['required', 'date', new ValidReservationTime()],
            'seats' => ['required', 'number', new In(static::VALID_SEATS)],
            'beers' => ['required', 'array', $seats ? "size:{$seats}" : ''],
            'beers.*' => [app(ValidBeer::class)],
            'meals' => ['required', 'array', $seats ? "size:{$seats}" : ''],
            'meals.*' => [app(ValidMeal::class)],
        ];
    }
}
