<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Role::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'slug' => $faker->unique()->randomElement(['listener', 'performer', 'critic', 'star']),
    ];
});
