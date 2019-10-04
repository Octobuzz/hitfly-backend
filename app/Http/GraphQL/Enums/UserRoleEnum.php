<?php

namespace App\Http\GraphQL\Enums;

use App\Dictionaries\RoleDictionary;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserRoleEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'UserRoleEnum',
        'description' => 'Типы ролй пользователя',
        'values' => [
            RoleDictionary::ROLE_STAR => RoleDictionary::ROLE_STAR,
            RoleDictionary::ROLE_PROF_CRITIC => RoleDictionary::ROLE_PROF_CRITIC,
            RoleDictionary::ROLE_CRITIC => RoleDictionary::ROLE_CRITIC,
        ],
    ];
}
