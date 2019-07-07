<?php

namespace App\Providers;

use App\Services\BeerService;
use App\Services\MealService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Carbon::resetToStringFormat();

        // bind guzzle client for beer service
        $this->app->when(BeerService::class)
            ->needs(Client::class)
            ->give(function () {
                return new Client([
                    'base_uri' => config('services.food.beer.url'),
                ]);
            })
        ;

        // bind guzzle client for meal service
        $this->app->when(MealService::class)
            ->needs(Client::class)
            ->give(function () {
                return new Client([
                    'base_uri' => config('services.food.meals.url'),
                ]);
            })
        ;
    }
}
