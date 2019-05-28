<?php

namespace App\Http\GraphQL\Type;

use App\Models\Comment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CommentAlbumType extends GraphQLType
{
    protected $attributes = [
        'name' => 'CommentAlbum',
        'model' => Comment::class,
        'description' => 'Отзыв к альбому',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'album' => [
                'type' => GraphQL::type('Album'),
            ],
            'comment' => [
                'type' => Type::string(),
            ],
            'createdAt' => [
                'type' => Type::string(),
                'alias' => 'created_at',
                'resolve' => function ($model) {
                    return $model->created_at;
                },
            ],
            'estimation' => [
                'type' => Type::int(),
            ],
        ];
    }
}
