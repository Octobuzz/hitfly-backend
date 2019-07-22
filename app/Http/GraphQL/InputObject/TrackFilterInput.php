<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TrackFilterInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'TrackFilterInput',
        'description' => 'Фильтры для треков',
    ];

    public function fields()
    {
        return [
            'my' => [
                'name' => 'my',
                'type' => Type::boolean(),
                'description' => 'Только мои треки',
                'rules' => ['mutually_exclusive_args:userId,musicGroupId,collectionId,playlistId,albumId'],
            ],
            'userId' => [
                'type' => Type::int(),
                'description' => 'ID пользователя(фильтрация)',
                'rules' => ['mutually_exclusive_args:my,musicGroupId,collectionId,playlistId,albumId'],
            ],
            'musicGroupId' => [
                'type' => Type::int(),
                'description' => 'ID группы(фильтрация)',
                'rules' => ['mutually_exclusive_args:my,userId,collectionId,playlistId,albumId'],
            ],
            'albumId' => [
                'type' => Type::int(),
                'description' => 'ID Альбома(фильтрация)',
                'rules' => ['mutually_exclusive_args:my,userId,collectionId,playlistId,musicGroupId'],
            ],
            'playlistId' => [
                'type' => Type::int(),
                'description' => 'ID плейлиста(фильтрация)',
                'rules' => ['mutually_exclusive_args:my,userId,collectionId,albumId,musicGroupId'],
            ],
            'collectionId' => [
                'type' => Type::int(),
                'description' => 'ID коллекции(фильтрация)',
                'rules' => ['mutually_exclusive_args:my,userId,playlistId,albumId,musicGroupId'],
            ],
            'iCommented' => [
                'type' => Type::boolean(),
                'description' => 'Треки откоментированные мною (для звезды, не обязательно). Работает совместно с commentPeriod',
                'rules' => ['mutually_exclusive_args:commentedByUser'],
            ],
            'commentedByUser' => [
                'type' => Type::int(),
                'description' => 'откомментированые пользователем(ID фильтрация) Работает совместно с commentPeriod',
                'rules' => ['mutually_exclusive_args:iCommented'],
            ],
            'genre' => [
                'type' => Type::int(),
                'description' => 'Фильтрация по жанру',
                'rules' => ['nullable', 'int'],
            ],
        ];
    }
}
