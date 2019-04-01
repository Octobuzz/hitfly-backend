<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Album::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->name,
        'author' => $faker->unique()->name,
        'year' => $faker->dateTime->format('Y'),
        'cover' => $faker->dateTime,
        'genre_id' => function () {
            return \App\Models\Genre::inRandomOrder()->first()->id;
        },
        'music_group_id' => function () {
            return \App\Models\MusicGroup::inRandomOrder()->first()->id;
        },
        'user_id' => function () {
            return \App\User::inRandomOrder()->first()->id;
        },
    ];
});
