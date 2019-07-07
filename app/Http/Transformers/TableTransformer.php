<?php

namespace App\Http\Transformers;

use App\Models\DTO\Meal;
use App\Models\Table;
use League\Fractal\TransformerAbstract;

class TableTransformer extends TransformerAbstract
{
    public function transform(Table $table): array
    {
        return [
            'id' => $table->id,
            'seats' => $table->seats,
        ];
    }
}
