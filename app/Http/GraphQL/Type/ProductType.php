<?php

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Fields\PictureField;
use App\Models\Product;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProductType extends GraphQLType
{
    protected $attributes = [
        'name' => 'ProductType',
        'description' => 'Товар',
        'model' => Product::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID товара',
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'название товара',
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'Описание товара',
            ],
            'detailDescription' => [
                'type' => Type::string(),
                'description' => 'Детальное описание товара',
                'alias' => 'detail_description',
            ],
            'alias' => [
                'type' => Type::string(),
                'description' => 'код товара',
            ],
            'price' => [
                'type' => Type::float(),
                'description' => 'цена товара',
            ],
            'image' => PictureField::class,
            'createdAt' => [
                'type' => Type::string(),
                'alias' => 'created_at',
                'resolve' => function ($model) {
                    return $model->created_at;
                },
                'description' => 'Дата создания',
            ],
        ];
    }
}
