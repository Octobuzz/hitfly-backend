<?php

namespace App\Http\GraphQL\InputObject\Filter;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class LifehackFilterInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'LifehackFilterInput',
        'description' => 'Фильтры для социальных ссылок',
    ];

    public function fields()
    {
        return [
            'tag' => [
                'name' => 'tag',
                'type' => Type::int(),
                'description' => 'фильтр по тэгу',
            ],
        ];
    }
}
