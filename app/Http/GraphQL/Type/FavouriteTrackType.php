<?php

namespace App\Http\GraphQL\Type;

use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class FavouriteTrackType extends GraphQLType
{
    protected $attributes = [
        'name' => 'FavouriteTrack',
        'model' => Track::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'track' => [
                'type' => GraphQL::type('Track'),
                'description' => 'трек',
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
