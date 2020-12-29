<?php

namespace App\Http\GraphQL\Enums;

use App\Models\Like;
use Rebing\GraphQL\Support\Type;

class LikeTypeEnum extends Type
{
    protected $enumObject = true;

    protected $attributes = [
        'name'        => 'LikeTypeEnum',
        'description' => 'типы лайков',
        'values'      => [
            Like::TYPE_LIFE_HACK => Like::TYPE_LIFE_HACK,
        ],
    ];
}
