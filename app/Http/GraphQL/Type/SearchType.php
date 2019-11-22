<?php

namespace App\Http\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SearchType extends GraphQLType
{
    protected $attributes = [
        'name' => 'SearchType',
    ];

    public function fields()
    {
        return [
            'Album' => [
                'type' => Type::listOf(\GraphQL::type('Album')),
                'description' => 'Альбомы',
            ],
            'Track' => [
                'type' => Type::listOf(\GraphQL::type('Track')),
                'description' => 'Треки ',
            ],
            'User' => [
                'type' => Type::listOf(\GraphQL::type('User')),
                'description' => 'Пользователи',
            ],
        ];
    }
}
