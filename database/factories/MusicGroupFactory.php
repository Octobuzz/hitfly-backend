<?php

use Faker\Generator as Faker;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

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

$factory->afterMaking(\App\Models\MusicGroup::class, function (\App\Models\MusicGroup $musicGroup, Faker $faker) {
    $image = new File($faker->image());
    $musicGroup->avatar_group = Storage::disk('public')->putFile('music_groups/'.$musicGroup->creator_group_id, $image);
});
