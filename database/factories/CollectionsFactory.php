<?php

use Faker\Generator as Faker;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

$factory->define(\App\Models\Collection::class, function (Faker $faker) {
    // $image = $faker->image();
    //$imageFile = new File($image);
    $user = \App\User::inRandomOrder()->first();

    return [
        'title' => $faker->sentence($nbWords = 4, $variableNbWords = true),
        //'image' => Storage::disk('public')->putFile('collections/'.$user->id, $imageFile),
        'user_id' => $user->id,
        'is_admin' => in_array($user->id, $user->has('roles')->pluck('id')->toArray()), //для всех администраторов, при необходимости роли необходимоуточнить
    ];
});

$factory->afterMaking(\App\Models\Collection::class, function (\App\Models\Collection $collection, Faker $faker) {
    if (App::environment('testing')) {
        $collection->image = '/'.implode('/', $faker->words($faker->numberBetween(1, 4))).$faker->word.'.jpg';
    } else {
        $image = new File($faker->image());
        $collection->image = Storage::disk('public')->putFile($collection->getPath(), $image);
    }
});
