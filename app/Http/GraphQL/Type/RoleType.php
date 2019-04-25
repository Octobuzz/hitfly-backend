<?php

namespace App\Http\GraphQL\Type;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class RoleType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Role',
        'description' => 'Роль пользователя',
    ];

    public function fields()
    {
        return [
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Имя роли',
            ],
            'slug' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'название роли(внутренний идентификатор)',
            ],

        ];
    }
}
