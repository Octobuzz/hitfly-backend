<?php

namespace App\Http\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SocialConnectType extends GraphQLType
{
    protected $attributes = [
        'name' => 'SocialConnectType',
    ];

    public function fields()
    {
        return [
            'social_type' => [
                'type' => Type::nonNull(\GraphQL::type('SocialLinksTypeEnum')),
                'description' => 'Тип социальной сети',
            ],
            'link' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Сссылка на привязку соц сети ',
            ],
            'connected' => [
                'type' => Type::boolean(),
                'description' => 'Привязана ли соц сеть',
            ],
        ];
    }
}
