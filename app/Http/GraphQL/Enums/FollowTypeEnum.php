<?php

namespace App\Http\GraphQL\Enums;

use App\Models\Watcheables;
use Rebing\GraphQL\Support\Type as GraphQLType;

class FollowTypeEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'FollowTypeEnum',
        'description' => 'Типы подписок',
        'values' => [
            Watcheables::TYPE_USER => Watcheables::TYPE_USER,
            Watcheables::TYPE_MUSIC_GROUP => Watcheables::TYPE_MUSIC_GROUP,
        ],
    ];
}
