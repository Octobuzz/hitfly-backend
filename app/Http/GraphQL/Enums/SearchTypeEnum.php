<?php

namespace App\Http\GraphQL\Enums;

use Rebing\GraphQL\Support\Type as GraphQLType;

class SearchTypeEnum extends GraphQLType
{
    protected $enumObject = true;

    protected $attributes = [
        'name' => 'SearchTypeEnum',
        'description' => 'Тип сущности для поиска',
        'values' => [
            'album' => 'album',
            'track' => 'track',
            'user' => 'user',
            ],
    ];
}
