<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Favourite::class, function (Faker $faker) {
    return[];
});

$factory->state(\App\Models\Favourite::class, 'life_hack', function (Faker $faker) {
    $favouriteableType = \App\Models\Lifehack::class;
    $favouriteableId = factory(\App\Models\Lifehack::class)->create()->id;

    return [
        'favouriteable_id' => $favouriteableId,
        'favouriteable_type' => $favouriteableType,
        'user_id' => function () {
            return \App\User::inRandomOrder()->first()->id;
        },
    ];
});
