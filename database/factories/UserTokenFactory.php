<?php

use App\Models\UserToken;
use Faker\Generator as Faker;

$factory->define(UserToken::class, function (Faker $faker) {
    return [
        'access_token' => $faker->sha1,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
    ];
});
