<?php

namespace App\Http\GraphQL\Type;

use App\Models\Comment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CommentTrackType extends GraphQLType
{
    protected $attributes = [
        'name' => 'CommentTrack',
        'model' => Comment::class,
        'description' => 'Отзыв к треку',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID',
            ],
            'comment' => [
                'type' => Type::string(),
                'description' => 'Комментарий',
            ],
            'user' => [
                'type' => \GraphQL::type('User'),
                'description' => 'Пользователь',
            ],
//            'track' => [
//                'type' => GraphQL::type('Track'),
//            ],
            'createdAt' => [
                'type' => Type::string(),
                'alias' => 'created_at',
                'resolve' => function ($model) {
                    return $model->created_at;
                },
                'description' => 'Дата создания',
            ],
            'estimation' => [
                'type' => Type::int(),
                'description' => 'Оценка',
            ],
        ];
    }
}
