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
        'description' => 'Жанры',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Имя жанра',
            ],
            'image' => [
                'type' => Type::string(),
                'description' => 'Логотип жанра',
            ],
            'userFavourite' => [
                'type' => Type::boolean(),
                'description' => 'флаг избранного жанра',
                'resolve' => function ($model) {
                    if ($model->userFavourite->count()) {
                        return true;
                    } else {
                        return false;
                    }
                },
                'privacy' => function (array $args) {
                    return \Auth::check();
                },
            ],
        ];
    }
}
