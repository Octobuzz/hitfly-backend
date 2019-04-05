<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class FavouriteInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'FavouriteInput',
        'description' => 'Избранное',
    ];

    public function fields()
    {
        return [
            'favouritableId' => [
                'name' => 'favouriteableId',
                'description' => 'id избранного',
                'type' => Type::nonNull(Type::int()),
                'rules' => ['max:250'],
            ],
            'favouriteable_type' => [
                'name' => 'favouritableType',
                'description' => 'Тип избранного',
                'type' => \GraphQL::type('FavouriteTypeEnum'),
                'rules' => ['max:250'],
            ],
        ];
    }
}
