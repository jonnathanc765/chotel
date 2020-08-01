<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Reservation;
use Faker\Generator as Faker;

$factory->define(Reservation::class, function (Faker $faker) {
    return [
        'check_in' => $faker->date,
        'check_out' => $faker->date
    ];
});
