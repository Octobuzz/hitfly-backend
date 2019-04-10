<?php

use Faker\Generator as Faker;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

$factory->define(\App\Models\Genre::class, function (Faker $faker) {
    $image = new File($faker->image());

    return [
        'name' => $faker->unique()->name,
        'image' => Storage::disk('public')->putFile('genres', $image),
    ];
});
