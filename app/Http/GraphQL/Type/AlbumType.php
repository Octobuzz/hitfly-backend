<?php

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Fields\PictureField;
use App\Http\GraphQL\Privacy\IsAuthPrivacy;
use App\Models\Album;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
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
            'type' => [
                'type' => Type::string(),
                'description' => 'Тип альбома',
            ],
            'genres' => [
                'type' => Type::listOf(GraphQL::type('Genre')),
                'description' => 'Жанры альбома',
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
            'cover' => PictureField::class,
            'musicGroup' => [
                'type' => GraphQL::type('MusicGroup'),
                'alias' => 'filename',
                'description' => 'Музыкальная группа',
            ],
            'userFavourite' => [
                'type' => Type::boolean(),
                'description' => 'флаг избранного альбома',
                'selectable' => false,
                'resolve' => function ($model) {
                    if ($model->userFavourite->count()) {
                        return true;
                    } else {
                        return false;
                    }
                },
                'privacy' => IsAuthPrivacy::class,
            ],
            'favouritesCount' => [
                'type' => Type::int(),
                'description' => 'Количество добавлений альбома в избранное',
                'resolve' => function ($model) {
                    return $model->favourites->count();
                },
                'selectable' => false,
            ],
            'my' => [
                'type' => Type::boolean(),
                'description' => 'мой альбом',
                'resolve' => function ($model) {
                    return $model->user_id === Auth::user()->id ? true : false;
                },
                'selectable' => false,
                'privacy' => IsAuthPrivacy::class,
            ],
            'tracksCount' => [
                'type' => Type::int(),
                'description' => 'Количество треков в альбоме',
                'resolve' => function ($model) {
                    return $model->tracks->count();
                },
                'selectable' => false,
            ],
            'tracksTime' => [
                'type' => Type::float(),
                'description' => 'Общее время треков',
                'resolve' => function ($model) {
                    return json_encode($model->tracksTime());
                },
                'selectable' => false,
            ],
        ];
    }
}
