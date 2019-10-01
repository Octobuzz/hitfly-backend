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
                'description' => 'Тип социальной сети',
            ],
            'Track' => [
                'type' => Type::listOf(\GraphQL::type('Track')),
                'description' => 'Сссылка на привязку соц сети ',
            ],
            'User' => [
                'type' => Type::listOf(\GraphQL::type('User')),
                'description' => 'Привязана ли соц сеть',
            ],
        ];
    }
}
