<?php

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Fields\PictureField;
use App\Models\BonusOperationType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class BonusTypesType extends GraphQLType
{
    protected $attributes = [
        'name' => 'BonusTypes',
        'model' => BonusOperationType::class,
        'description' => 'Список на что можно постратить бонусные балы',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Id',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Название',
            ],
            'constantName' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'название константы',
                'alias' => 'constant_name',
                'resolve' => function ($model) {
                    return $model->constant_name;
                },
            ],
            'bonus' => [
                'type' => Type::int(),
                'description' => 'Стоимость',
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'Описание',
            ],
            'img' => PictureField::class,
        ];
    }
}
