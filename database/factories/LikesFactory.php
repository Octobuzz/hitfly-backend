<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Like::class, function (Faker $faker) {
    $likesType = [
        App\Models\Track::class,
        App\Models\Album::class,
    ];
    /** @var \Illuminate\Database\Eloquent\Model $likeableType */
    $likeableType = $faker->randomElement($likesType);
    $likeableId = $likeableType::inRandomOrder()->first()->id;

    return [
        'likeable_type' => $likeableType,
        'likeable_id' => $likeableId,
        'user_id' => function () {
            return \App\User::inRandomOrder()->first()->id;
        },
    ];
});
