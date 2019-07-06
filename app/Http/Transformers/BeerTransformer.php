<?php


namespace App\Http\Transformers;


use App\Models\DTO\Beer;
use League\Fractal\TransformerAbstract;

class BeerTransformer extends TransformerAbstract
{
    public function transform(Beer $beer): array
    {
        return [
            'id' => $beer->id,
            'name' => $beer->name,
            'description' => $beer->description,
            'abv' => $beer->abv,
        ];
    }

}