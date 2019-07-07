<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('users')->group(function () {
    Route::prefix('{email}/reservations')->group(function () {
        Route::get('', 'ReservationController@all');
        Route::get('/{bookingId}', 'ReservationController@get')->where('bookingId', '[0-9]+');
        Route::post('', 'ReservationController@create');
    });
});

Route::prefix('meals')->group(function () {
    Route::get('', 'MealController@all');
    Route::get('/{mealId}', 'MealController@get')->where('mealId', '[0-9]+');
});

Route::prefix('beers')->group(function () {
    Route::get('', 'BeerController@all');
    Route::get('/{beerId}', 'BeerController@get')->where('beerId', '[0-9]+');
});
