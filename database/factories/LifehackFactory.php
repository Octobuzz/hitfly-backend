<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Lifehack::class, function (Faker $faker) {
    return [
        'image' => $faker->imageUrl(),
        'title' => $faker->text(),
        'sort' => 500,
    ];
});
