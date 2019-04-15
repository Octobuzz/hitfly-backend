<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Favourite::class, function (Faker $faker) {
    $favouriteable = [
        App\Models\Track::class,
        App\Models\Album::class,
        App\Models\Genre::class,
    ];
    $favouriteableType = $faker->randomElement($favouriteable);
    $favouriteableId = $favouriteableType::inRandomOrder()->first()->id;

    return [
        'favouriteable_id' => $favouriteableId,
        'favouriteable_type' => $favouriteableType,
        'user_id' => function () {
            return \App\User::inRandomOrder()->first()->id;
        },
    ];
});
