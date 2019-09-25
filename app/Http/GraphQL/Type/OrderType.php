<?php

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Fields\ProductAttributeField;
use App\Models\Order;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class OrderType extends GraphQLType
{
    protected $attributes = [
        'name' => 'OrderType',
        'description' => 'Заказы',
        'model' => Order::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID заказа',
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'название заказа',
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'Описание заказа',
            ],
            'users' => [
                'type' => Type::nonNull(\GraphQL::type('User')),
                'description' => 'Пользователь(заказчик)',
            ],
            'products' => [
                'type' => Type::nonNull(\GraphQL::type('ProductType')),
                'description' => 'Товар',
            ],
            'status' => [
                'type' => Type::string(),
                'description' => 'статус заказа',
            ],
            'productAttribute' => ProductAttributeField::class,
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
