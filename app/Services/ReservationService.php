<?php

namespace App\Services;

use App\Exceptions\NoTablesException;
use App\Reservation;
use App\Table;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    public function create(User $user, Carbon $date, int $seats, array $beers, array $meals)
    {
        return DB::transaction(function () use ($user, $date, $seats, $beers, $meals) {
            $freeTables = $this->tablesFreeByDate($date);

            if ($freeTables->sum('seats') < $seats) {
                throw new NoTablesException();
            }

            $reservation = new Reservation([
                'time' => $date,
            ]);

            $reservation->user()->save($user);

            $usedSeats = 0;
            while ($usedSeats < $seats) {
                $table = $freeTables->shift();
                $reservation->tables()->save($table);
            }

            $reservation->saveBeers($beers);
            $reservation->saveMeels($meals);

            return $reservation;
        });
    }

    protected function tablesFreeByDate(Carbon $date): Collection
    {
        return Table::whereDoesntHave('reservations', function (Builder $query) use ($date): void {
            $query->whereDate('time', $date);
        })->get();
    }
}
