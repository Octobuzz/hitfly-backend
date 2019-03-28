<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\MusicGroup::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'description' => $faker->paragraph,
        'career_start_year' => $faker->dateTime,
        'genre_id' => function () {
            return \App\Models\Genre::inRandomOrder()->first()->id;
        },
        'creator_group_id' => function () {
            return \App\User::inRandomOrder()->first()->id;
        },
    ];
});
