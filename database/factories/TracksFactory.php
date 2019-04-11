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
        'state' => 'fileload',
        'user_id' => function () {
            return \App\User::inRandomOrder()->first()->id;
        },
    ];
});

$factory->afterMaking(\App\Models\Track::class, function (\App\Models\Track $track, Faker $faker) {
    $file = new \Illuminate\Http\File($faker->file(Storage::disk('local')->path('mp3'), Storage::disk('local')->path('tmp')));
    $track->filename = Storage::disk('public')->putFile('tracks/'.$track->user_id, $file);
});
