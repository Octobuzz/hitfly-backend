<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CollectionFilterInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'CollectionFilterInput',
        'description' => 'Фильтры для Коллекций',
    ];

    public function fields()
    {
        return [
            'my' => [
                'name' => 'my',
                'type' => Type::boolean(),
                'description' => 'Только мои коллекции',
                'rules' => ['mutually_exclusive_args:userId, collection, superMusicFan'],
            ],
            'userId' => [
                'type' => Type::int(),
                'description' => 'ID пользователя(фильтрация)',
                'rules' => ['mutually_exclusive_args:my, superMusicFan, collection'],
            ],
            'collection' => [
                'type' => Type::boolean(),
                'description' => 'Подборка',
                'rules' => ['mutually_exclusive_args:my, superMusicFan,userId'],
            ],
            'superMusicFan' => [
                'type' => Type::boolean(),
                'description' => 'Супермеломан',
                'rules' => ['mutually_exclusive_args:my,userId, collection'],
            ],
        ];
    }
}
