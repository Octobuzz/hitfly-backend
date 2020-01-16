<?php

use Faker\Generator as Faker;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

$factory->define(\App\Models\Genre::class, function (Faker $faker) {
        return [
            'name' => $faker->unique()->name,
            'image' => getImage($faker),
        ];
});

function getImage(Faker $faker){
    if (App::environment('testing')) {
        return '/' . implode('/', $faker->words($faker->numberBetween(1, 4))).$faker->word . '.jpg';
    }
    $image = new File($faker->image());
    return Storage::disk('public')->putFile('genres', $image);
}
