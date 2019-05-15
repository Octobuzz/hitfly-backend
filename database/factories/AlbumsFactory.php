<?php

use Faker\Generator as Faker;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

$factory->define(\App\Models\Album::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->name,
        'author' => $faker->unique()->name,
        'year' => $faker->dateTime->format('Y-m-d'),
        'user_id' => function () {
            return \App\User::inRandomOrder()->first()->id;
        },
        'music_group_id' => function () {
            return \App\Models\MusicGroup::inRandomOrder()->first()->id;
        },
        'type' => function () {
            $type = [
                App\Models\Album::TYPE_ALBUM,
                App\Models\Album::TYPE_SINGLE,
                App\Models\Album::TYPE_EP,
                App\Models\Album::TYPE_COLLECTION,
            ];

            return $type[rand(1, 3)];
        },
    ];
});

$factory->afterMaking(\App\Models\Album::class, function (\App\Models\Album $album, Faker $faker) {
    $image = new File($faker->image());
    $album->cover = Storage::disk('public')->putFile($album->getPath(), $image);
});
