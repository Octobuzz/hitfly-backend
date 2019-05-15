<?php

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Fields\AlbumCoverField;
use App\Models\Album;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class AlbumType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Album',
        'model' => Album::class,
        'description' => 'Музыкальный альбом',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id',
            ],
            'title' => [
                'type' => Type::string(),
                'description' => 'Название',
            ],
            'genre' => [
                'type' => GraphQL::type('Genre'),
                'description' => 'Жанр альбома',
            ],
            'user' => [
                'type' => GraphQL::type('User'),
                'description' => 'Пользователь альбома',
            ],
            'author' => [
                'type' => Type::string(),
                'description' => 'Автор',
            ],
            'year' => [
                'type' => Type::string(),
                'description' => 'Год выпуска',
            ],
            'cover' => AlbumCoverField::class,
            'musicGroup' => [
                'type' => GraphQL::type('MusicGroup'),
                'alias' => 'filename',
                'description' => 'Музыкальная группа',
            ],
            'userFavourite' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'флаг избранного альбома',
                'selectable' => false,
                'resolve' => function ($model) {
                    if ($model->userFavourite->count()) {
                        return true;
                    } else {
                        return false;
                    }
                },
            ],
        ];
    }
}
