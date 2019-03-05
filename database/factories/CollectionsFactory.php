<?php

use Faker\Generator as Faker;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

$factory->define(\App\Models\Collection::class, function (Faker $faker) {

    $image = $faker->image();
    $imageFile = new File($image);
    $user = \App\User::inRandomOrder()->first();

    return [
        'title' => $faker->sentence($nbWords = 4, $variableNbWords = true),
        'image' => Storage::disk('public')->putFile('images', $imageFile),
        'user_id' => $user->id,
        'is_admin' => in_array($user->id, $user->has('roles')->pluck('id')->toArray()),//для всех администраторов, при необходимости роли необходимоуточнить
    ];
});

