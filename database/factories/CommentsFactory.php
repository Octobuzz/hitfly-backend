<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Comment::class, function (Faker $faker) {
    $commentables = [
        App\Models\Track::class,
        //App\Models\Album::class,
    ];
    $commentableType = $faker->randomElement($commentables);
    $commentableId = $commentableType::withoutGlobalScope('state')->inRandomOrder()->first()->id;

    return [
        'commentable_id' => $commentableId,
        'commentable_type' => $commentableType,
        'user_id' => function () {
            return \App\User::inRandomOrder()->first()->id;
        },
        'comment' => $faker->paragraph,
        'estimation' => rand(1, 5),
    ];
});
