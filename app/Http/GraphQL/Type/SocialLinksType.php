<?php

namespace App\Http\GraphQL\Type;

use App\Models\GroupLinks;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SocialLinksType extends GraphQLType
{
    protected $attributes = [
        'name' => 'SocialLinks',
        'model' => GroupLinks::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'social_type' => [
                'type' => Type::nonNull(\GraphQL::type('SocialLinksTypeEnum')),
                'description' => 'социальная сеть',
            ],
            'link' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'ссылка на профиль соцсети',
            ],
            'createdAt' => [
                'type' => Type::string(),
                'alias' => 'created_at',
                'resolve' => function ($model) {
                    return $model->created_at;
                },
                'description' => 'дата создания',
            ],
        ];
    }
}
