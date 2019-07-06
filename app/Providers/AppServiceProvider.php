<?php

namespace App\Providers;

use App\Services\BeverageService;
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
        // bind guzzle client for beverage service
        $this->app->when(BeverageService::class)
            ->needs(Client::class)
            ->give(function () {
                return new Client([
                    'base_uri' => config('services.food.beverages.url'),
                ]);
            })
        ;
    }
}
