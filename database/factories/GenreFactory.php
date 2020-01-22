<?php

use Faker\Generator as Faker;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

$factory->define(\App\Models\Genre::class, function (Faker $faker) {
    $genre = [];
    $genre['name'] = $faker->unique()->name;
    if (App::environment('testing')) {
        $genre['image'] = '/'.implode('/', $faker->words($faker->numberBetween(1, 4))).$faker->word.'.jpg';
    } else {
        $image = new File($faker->image());
        $genre['image'] = Storage::disk('public')->putFile('genres', $image);
    }

    return $genre;
});
