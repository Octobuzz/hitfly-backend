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
            'iCommented' => [
                'type' => Type::boolean(),
                'description' => 'Треки откоментированные мною (для звезды, не обязательно). Работает совместно с commentPeriod',
            ],
        ];
    }
}
