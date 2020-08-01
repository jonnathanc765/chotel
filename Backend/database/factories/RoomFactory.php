<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Room;
use Faker\Generator as Faker;

$factory->define(Room::class, function (Faker $faker) {

    $path = public_path() . '/storage/images/';

    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }

    $path = str_replace('public/storage/', '', $faker->file($fuente = 'resources/images/rooms', $destino = 'public/storage/rooms'));

    return [
        'price' => rand(2, 30),
        'place' => 'floor #' . rand(1,40)
    ];
});
