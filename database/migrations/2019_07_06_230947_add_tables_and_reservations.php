<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTablesAndReservations extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('seats')->default(2);

            $table->timestamps();
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('time');

            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
        });

        Schema::create('reservation_table', function (Blueprint $table) {
            $table->unsignedBigInteger('table_id');
            $table->unsignedBigInteger('reservation_id');

            $table->foreign('table_id')->references('id')->on('tables');
            $table->foreign('reservation_id')->references('id')->on('reservations');
        });

        Schema::create('meal_reservation', function (Blueprint $table) {
            $table->unsignedBigInteger('meal_id');
            $table->unsignedBigInteger('reservation_id');

            $table->foreign('meal_id')->references('id')->on('meals');
            $table->foreign('reservation_id')->references('id')->on('reservations');
        });

        Schema::create('beer_reservation', function (Blueprint $table) {
            $table->unsignedBigInteger('beer_id');
            $table->unsignedBigInteger('reservation_id');

            $table->foreign('beer_id')->references('id')->on('beers');
            $table->foreign('reservation_id')->references('id')->on('reservations');
        });

        Schema::create('meals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('external_id');
        });

        Schema::create('beers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('external_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('beer_reservation');
        Schema::dropIfExists('meal_reservation');
        Schema::dropIfExists('meals');
        Schema::dropIfExists('beers');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('tables');
    }
}
