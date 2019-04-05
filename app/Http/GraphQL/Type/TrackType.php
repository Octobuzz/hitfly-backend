<?php

namespace App\Http\GraphQL\Type;

use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class TrackType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Track',
        'model' => Track::class,
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
            ],
            'album' => [
                'type' => GraphQL::type('Album'),
            ],
            'genre' => [
                'type' => GraphQL::type('Genre'),
            ],
            'user' => [
                'type' => GraphQL::type('User'),
            ],
            'singer' => [
                'type' => Type::string(),
            ],
            'trackDate' => [
                'type' => Type::string(),
                'resolve' => function ($model) {
                    return $model->track_date;
                },
                'alias' => 'track_date',
            ],
            'songText' => [
                'type' => Type::nonNull(Type::string()),
                'alias' => 'song_text',
                'resolve' => function ($model) {
                    return $model->song_text;
                },
            ],
            'filename' => [
                'type' => Type::nonNull(Type::string()),
                'alias' => 'filename',
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
            ],
        ];
    }
}
