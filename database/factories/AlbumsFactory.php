<?php

use Faker\Generator as Faker;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

$factory->define(\App\Models\Album::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->name,
        'author' => $faker->unique()->name,
        'year' => $faker->dateTime->format('Y'),
        'user_id' => function () {
            return \App\User::inRandomOrder()->first()->id;
        },
        'genre_id' => function () {
            return \App\Models\Genre::inRandomOrder()->first()->id;
        },
        'music_group_id' => function () {
            return \App\Models\MusicGroup::inRandomOrder()->first()->id;
        },
    ];
});

$factory->afterMaking(\App\Models\Album::class, function (\App\Models\Album $album, Faker $faker) {
    $image = new File($faker->image());
    $album->cover = Storage::disk('public')->putFile('albums/'.$album->user_id, $image);
});
