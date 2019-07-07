<?php

namespace App\Services;

use App\Exceptions\NoTablesException;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    public function all(User $user): Collection
    {
        return $this->reservationsByUser($user)->get();
    }

    public function get(User $user, int $id): Reservation
    {
        /** @var Reservation $reservation */
        $reservation = $this->reservationsByUser($user)->findOrFail($id);

        return $reservation;
    }

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

            $reservation->user()->associate($user);
            $reservation->save();

            $usedSeats = 0;
            while ($usedSeats < $seats) {
                $table = $freeTables->shift();
                $reservation->tables()->save($table);

                $usedSeats = $usedSeats + $table->seats;
            }

            $reservation->saveBeers($beers);
            $reservation->saveMeals($meals);

            return $reservation;
        });
    }

    protected function reservationsByUser(User $user): Builder
    {
        // secure that user can't fetch each others reservations
        return Reservation::whereHas('user', function(Builder $query) use ($user){
            $query->where('email', $user->email);
        });
    }

    protected function tablesFreeByDate(Carbon $date): Collection
    {
        return Table::whereDoesntHave('reservations', function (Builder $query) use ($date): void {
            $query->whereDate('time', $date);
        })->get();
    }
}
