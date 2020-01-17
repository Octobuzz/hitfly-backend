<?php

use Faker\Generator as Faker;
use Illuminate\Http\File;

$factory->define(\App\Models\Track::class, function (Faker $faker) {
    return [
        'track_name' => $faker->words($faker->numberBetween(1,3), true),
        'album_id' => function () {
            return \App\Models\Album::inRandomOrder()->first()->id;
        },
        'genre_id' => function () {
            return \App\Models\Genre::inRandomOrder()->first()->id;
        },
        'track_date' => $faker->dateTimeAD,
        'track_hash' => $faker->md5,
        'song_text' => $faker->paragraph,
        'length' => $faker->randomFloat(2, 100, 500),
        'state' => \App\Models\Track::PUBLISHED,
        'user_id' => function () {
            return \App\User::inRandomOrder()->first()->id;
        },
    ];
});

$factory->afterMaking(\App\Models\Track::class, function (\App\Models\Track $track, Faker $faker) {
    if (App::environment('testing')) {
        $track->filename = '/' . implode('/', $faker->words($faker->numberBetween(1, 4))).$faker->word . '.jpg';
        //$track->cover = '/' . implode('/', $faker->words($faker->numberBetween(1, 4))).$faker->word . '.jpg';

    }else {
        $file = new \Illuminate\Http\File($faker->file(Storage::disk('local')->path('mp3'), Storage::disk('local')->path('tmp')));
        $track->filename = Storage::disk('public')->putFile('tracks/' . $track->user_id, $file);

        $image = new File($faker->image());
        $track->cover = Storage::disk('public')->putFile($track->getPath(), $image);
    }
});
