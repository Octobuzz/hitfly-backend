<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AlbumFilterInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'AlbumFilterInput',
        'description' => 'Фильтры для альбомов',
    ];

    public function fields()
    {
        return [
            'my' => [
                'name' => 'my',
                'type' => Type::boolean(),
                'description' => 'Только мои альбомы',
                'rules' => ['mutually_exclusive_args:userId,musicGroupId'],
            ],
            'userId' => [
                'type' => Type::int(),
                'description' => 'ID пользователя(фильтрация)',
                'rules' => ['mutually_exclusive_args:my,musicGroupId'],
            ],
            'musicGroupId' => [
                'type' => Type::int(),
                'description' => 'ID группы(фильтрация)',
                'rules' => ['mutually_exclusive_args:my,userId'],
            ],
        ];
    }
}
