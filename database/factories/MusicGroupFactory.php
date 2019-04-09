<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\MusicGroup::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'description' => $faker->paragraph,
        'career_start_year' => $faker->dateTime,
        'creator_group_id' => function () {
            return \App\User::inRandomOrder()->first()->id;
        },
        'city_id' => function () {
            return \App\Models\City::inRandomOrder()->first()->id;
        },
    ];
});
