<?php

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Fields\CommentsTrackField;
use App\Http\GraphQL\Fields\PictureField;
use App\Http\GraphQL\Privacy\IsAuthPrivacy;
use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class TrackType extends GraphQLType
{
    use GraphQLAuthTrait;
    protected $attributes = [
        'name' => 'Track',
        'model' => Track::class,
        'description' => 'Трек',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID трека',
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
                'resolve' => function ($model) {
                    $singer = $model->getAuthor();
                    if ('admin' === strtolower($singer)) {
                        $singer = '';
                    }

                    return $singer;
                },
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
                'type' => Type::boolean(),
                'description' => 'флаг избранного трека',
                'privacy' => IsAuthPrivacy::class,
                'resolve' => function ($model) {
                    if ($model->userFavourite->count()) {
                        return true;
                    }

                    return false;
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
            'length' => [
                'type' => Type::float(),
                'description' => 'длина трека, МОЖЕТ БЫТЬ ПУСТОЕ ЗНАЧЕНИЕ (NULL) !!!',
            ],
            'my' => [
                'type' => Type::boolean(),
                'description' => 'Мой трек',
                'resolve' => function ($model) {
                    if ($model->user_id === $this->getGuard()->user()->id) {
                        return true;
                    } else {
                        return false;
                    }
                },
                'selectable' => false,
                'privacy' => IsAuthPrivacy::class,
            ],
            'commentedByMe' => [
                'type' => Type::boolean(),
                'description' => 'Откомментирован мной',
                'resolve' => function ($model) {
                    if ($model->comments()->where('user_id', $this->getGuard()->user()->id)->count() > 0) {
                        return true;
                    } else {
                        return false;
                    }
                },
                'selectable' => false,
                'privacy' => IsAuthPrivacy::class,
            ],
        ];
    }
}
