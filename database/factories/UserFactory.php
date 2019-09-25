<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'username' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'gender' => $faker->randomElement(['male', 'female']),
        'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'gender' => $faker->randomElement(['M', 'F']),
        'access_token' => $faker->md5,
        'city_id' => function () {
            return \App\Models\City::inRandomOrder()->first()->id;
        },
    ];
});

$factory->afterCreating(App\User::class, function (App\User $user, Faker $faker) {
    $user->roles()->save(\Encore\Admin\Auth\Database\Role::where('id', '>', 1)->inRandomOrder()->first());
    if ($user->inRoles(['star', 'performer'])) {
        $artist = new App\Models\ArtistProfile();
        $artist->user_id = $user->id;
        $artist->description = $faker->text;
        $artist->career_start = $faker->date($format = 'Y-m-d', $max = 'now');
        $artist->save();
        $artist->genres()->saveMany(\App\Models\Genre::inRandomOrder()->take(3)->get());
    }
});
