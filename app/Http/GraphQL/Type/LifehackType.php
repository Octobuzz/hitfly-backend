<?php

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Fields\PictureField;
use App\Models\Lifehack;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class LifehackType extends GraphQLType
{
    protected $attributes = [
        'name' => 'LifehackType',
        'model' => Lifehack::class,
        'description' => 'Лайфхак',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::int(),
                'description' => 'ID лайфхака',
            ],
            'title' => [
                'type' => Type::string(),
                'description' => 'Заголовок',
            ],

            'image' => PictureField::class,
            'hasFavorite' => [
                'type' => Type::boolean(),
                'description' => 'В избранном',
                'resolve' => function ($model) {
                    return $model->favorite->count() > 0;
                },
                'selectable' => false,
            ],
        ];
    }
}
