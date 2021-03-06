<?php

namespace App\Http\GraphQL\Type;

use App\Models\Watcheables;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class FollowMusicGroupType extends GraphQLType
{
    protected $attributes = [
        'name' => 'FollowMusicGroup',
        'model' => Watcheables::class,
        'description' => 'Подписка на Музыкальную группу(Follower)',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'musicGroup' => [
                'type' => GraphQL::type('MusicGroup'),
                'description' => 'Музыкальная группа',
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
