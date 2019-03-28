<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Track::class, function (Faker $faker) {
    return [
        'track_name' => $faker->name,
        'album_id' => function () {
            return \App\Models\Album::inRandomOrder()->first()->id;
        },
        'genre_id' => function () {
            return \App\Models\Genre::inRandomOrder()->first()->id;
        },
        'singer' => $faker->name,
        'track_date' => $faker->dateTimeAD,
        'song_text' => $faker->paragraph,
        'track_hash' => $faker->md5,
        'filename' => $faker->streetAddress, //todo Доделать сделать врменный файл
        'state' => 'fileload',
        'user_id' => function () {
            return \App\User::inRandomOrder()->first()->id;
        },
    ];
});
