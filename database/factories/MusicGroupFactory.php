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
    $musicGroup->avatar_group = Storage::disk('public')->putFile($musicGroup->getPath(), $image);
});
$factory->afterCreating(\App\Models\MusicGroup::class, function (\App\Models\MusicGroup $musicGroup, Faker $faker) {
    $groupLink = new \App\Models\GroupLinks();
    $groupLink->music_group_id = $musicGroup->id;
    $types = $groupLink->getPossibleTypes();
    $groupLink->social_type = $types[array_rand($types)];
    $groupLink->link = $faker->url;
    $groupLink->save();

    $invite = new \App\Models\InviteToGroup();
    $invite->music_group_id = $musicGroup->id;
    $invite->email = $faker->email;
    $invite->user_id = \App\User::inRandomOrder()->first()->id;
    $invite->accept = 1;
    $invite->save();
});
