<?php

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Fields\CommentsTrackField;
use App\Http\GraphQL\Fields\PictureField;
use App\Http\GraphQL\Privacy\IsAuthPrivacy;
use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class TrackType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Track',
        'model' => Track::class,
        'description' => 'Трек',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::int(),
            ],
            'trackName' => [
                'type' => Type::string(),
                'alias' => 'track_name',
                'resolve' => function ($model) {
                    return $model->track_name;
                },
                'description' => 'Имя трека',
            ],
            'album' => [
                'type' => GraphQL::type('Album'),
                'description' => 'Альбом',
            ],
            'genres' => [
                'type' => Type::listOf(GraphQL::type('Genre')),
                'description' => 'Жанры',
            ],
            'user' => [
                'type' => GraphQL::type('User'),
                'description' => 'Кто загрузил трек',
            ],
            'musicGroup' => [
                'type' => GraphQL::type('MusicGroup'),
                'description' => 'Музыкальная группа, к которой принадлежит трек',
            ],
            'singer' => [
                'type' => Type::string(),
                'description' => 'Испольнитель',
            ],
            'trackDate' => [
                'type' => Type::string(),
                'resolve' => function ($model) {
                    return $model->track_date;
                },
                'alias' => 'track_date',
                'description' => 'Дата трека',
            ],
            'songText' => [
                'type' => Type::nonNull(Type::string()),
                'alias' => 'song_text',
                'resolve' => function ($model) {
                    return $model->song_text;
                },
                'description' => 'Текст',
            ],
            'filename' => [
                'type' => Type::nonNull(Type::string()),
                'alias' => 'filename',
                'description' => 'Полный URL до трека',
                'resolve' => function ($model) {
                    return $model->getUrl();
                },
            ],
            'userFavourite' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'флаг избранного трека',
                'resolve' => function ($model) {
                    if ($model->userFavourite->count()) {
                        return true;
                    } else {
                        return false;
                    }
                },
                'selectable' => false,
            ],
            'favouritesCount' => [
                'type' => Type::int(),
                'description' => 'Количество добавлений трека в избранное',
                'resolve' => function ($model) {
                    return $model->favourites->count();
                },
                'selectable' => false,
            ],
            'userPlayLists' => [
                'type' => Type::listOf(GraphQL::type('Collection')),
                'description' => 'PlayLists пользователя. Только для авторизированных пользователей',
                'privacy' => IsAuthPrivacy::class,
            ],
            'musicWave' => [
                'type' => Type::listOf(Type::int()),
                'description' => 'Музыкальная волна',
                'alias' => 'music_wave',
            ],
            'cover' => PictureField::class,
            'comments' => CommentsTrackField::class,
            'my' => [
                'type' => Type::boolean(),
                'description' => 'Мой трек',
                'resolve' => function ($model) {
                    if ($model->user_id === Auth::user()->id) {
                        return true;
                    } else {
                        return false;
                    }
                },
                'selectable' => false,
            ],
        ];
    }
}
