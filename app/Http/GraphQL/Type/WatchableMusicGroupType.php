<?php

namespace App\Http\GraphQL\Type;

use App\Models\Favourite;
use App\Models\Watcheables;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class WatchableMusicGroupType extends GraphQLType
{
    protected $attributes = [
        'name' => 'WatchableMusicGroup',
        'model' => Watcheables::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'group' => [
                'type' => GraphQL::type('MusicGroup'),
                'description' => 'Группа за которой следит',
            ],
            'userId' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID пользователя',
                'alias' => 'user_id',
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
