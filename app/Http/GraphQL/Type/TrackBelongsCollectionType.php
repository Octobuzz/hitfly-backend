<?php

namespace App\Http\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TrackBelongsCollectionType extends GraphQLType
{
    protected $attributes = [
        'name' => 'TrackBelongsCollection',
        'description' => 'Трек относится к коллекции',
    ];

    public function fields()
    {
        return [
            'inCollection' => [
                'type' => Type::boolean(),
                'resolve' => function ($model) {
                    return $model;
                },
                'selectable' => false,
            ],
        ];
    }
}
