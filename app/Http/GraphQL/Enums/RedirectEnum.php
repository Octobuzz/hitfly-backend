<?php

namespace App\Http\GraphQL\Enums;

use Rebing\GraphQL\Support\Type as GraphQLType;

class RedirectEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'RedirectEnum',
        'description' => 'Редирект',
        'values' => [
            'TRACK_UPLOAD' => 'TRACK_UPLOAD',
            'SPEND_BONUSES' => 'SPEND_BONUSES',
            'HOME' => 'HOME',
            ],
    ];
}
