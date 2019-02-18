<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Comment::class, function (Faker $faker) {
    return [
        'track_id' => function(){
            return \App\Models\Track::inRandomOrder()->first()->id;
        },
        'album_id' => function(){
            return \App\Models\Album::inRandomOrder()->first()->id;
        },
        'user_id' => function(){
            return \App\User::inRandomOrder()->first()->id;
        },
        'comment' => $faker->paragraph,
        'comment_date' => $faker->dateTime,
        'estimation' => rand(1,5),
    ];
});
