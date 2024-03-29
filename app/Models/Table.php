<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Table extends Model
{
    protected $casts = [
        'seats' => 'int',
    ];

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class);
    }
}
