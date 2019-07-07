<?php

namespace App\Http\Transformers;

use App\Models\Reservation;
use League\Fractal\TransformerAbstract;

class ReservationTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'beers',
        'meels',
    ];

    public function transform(Reservation $reservation): array
    {
        return [
            'id' => $reservation->id,
            'time' => $reservation->time,
        ];
    }
}
