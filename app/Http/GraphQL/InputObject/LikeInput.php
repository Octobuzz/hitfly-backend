<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL;
use GraphQL\Type\Definition\Type;

class LikeInput extends \Rebing\GraphQL\Support\Type
{
    protected $inputObject = true;

    protected $attributes = [
        'name'        => 'LikeInput',
        'description' => 'Лайк',
    ];

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'likeableId' => [
                'name'        => 'likeableId',
                'description' => 'ID типа',
                'type'        => Type::nonNull(Type::int()),
                'rules'       => ['max:250'],
            ],
            'likeableType' => [
                'name'        => 'likeableType',
                'description' => 'Тип',
                'type'        => GraphQL::type('LikeTypeEnum'),
                'rules'       => ['max:250'],
            ],
        ];
    }
}
