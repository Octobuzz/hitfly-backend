<?php

namespace App\Http\GraphQL\Enums;

use App\User;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserRoleEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'UserRoleEnum',
        'description' => 'Типы ролй пользователя',
        'values' => [
            User::ROLE_STAR => User::ROLE_STAR,
            User::ROLE_PROF_CRITIC => User::ROLE_PROF_CRITIC,
            User::ROLE_CRITIC => User::ROLE_CRITIC,
        ],
    ];
}
