<?php

namespace App\Http\Transformers;

use App\Models\DTO\Meal;
use League\Fractal\TransformerAbstract;

class MealTransformer extends TransformerAbstract
{
    public function transform(Meal $meal): array
    {
        return [
            'id' => $meal->id,
            'name' => $meal->name,
            'type' => $meal->type,
        ];
    }
}
