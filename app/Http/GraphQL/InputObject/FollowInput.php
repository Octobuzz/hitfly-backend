<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class FollowInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'FollowInput',
        'description' => 'Подписка',
    ];

    public function fields()
    {
        return [
            'FollowId' => [
                'name' => 'FollowId',
                'description' => 'id Подписки',
                'type' => Type::nonNull(Type::int()),
                'rules' => ['max:250'],
            ],
            'FollowType' => [
                'name' => 'FollowType',
                'description' => 'Тип подписки',
                'type' => \GraphQL::type('FollowTypeEnum'),
                'rules' => ['max:250'],
            ],
        ];
    }
}
