<?php

namespace App\Http\GraphQL\Type;

use App\Models\Genre;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class GenreType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Genre',
        'model' => Genre::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the ',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The id of the ',
            ],
            'image' => [
                'type' => Type::string(),
                'description' => 'The id of the ',
            ],
            'userFavourite' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'флаг избранного жанра',
                'resolve' => function ($model) {
                    if($model->userFavourite->count())
                        return true;
                    else
                        return false;
                },

            ],
        ];
    }
}
