<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Watching::class, function (Faker $faker) {
    $watchListables = [
        App\Models\MusicGroup::class,
        App\User::class,
    ];

    $watchListableType = $faker->randomElement($watchListables);
    $watchListableId = $watchListableType::inRandomOrder()->first()->id;

    return [
        'watcheable_id' => $watchListableId,
        'watcheable_type' => $watchListableType,
        'user_id' => function () {
            return \App\User::inRandomOrder()->first()->id;
        },
    ];
});
