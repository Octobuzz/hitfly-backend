<?php

namespace App\Http\GraphQL\Enums;

use App\User;
use Rebing\GraphQL\Support\Type as GraphQLType;

class BonusProgramUserStatusEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'BonusProgramUserStatusEnum',
        'description' => 'Текущий статус пользователя в бонусной программе',
        'values' => [
            User::LEVEL_NOVICE => User::LEVEL_NOVICE,
            User::LEVEL_AMATEUR => User::LEVEL_AMATEUR,
            User::LEVEL_CONNOISSEUR_OF_THE_GENRE => User::LEVEL_CONNOISSEUR_OF_THE_GENRE,
            User::LEVEL_SUPER_MUSIC_LOVER => User::LEVEL_SUPER_MUSIC_LOVER,
        ],
    ];
}
