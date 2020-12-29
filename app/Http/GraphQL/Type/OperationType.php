<?php

namespace App\Http\GraphQL\Type;

use App\Models\Operation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class OperationType extends GraphQLType
{
    protected $attributes = [
        'name' => 'OperationType',
        'description' => 'Операции',
        'model' => Operation::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID операции',
                'alias' => 'id', // Use 'alias', if the database column is different from the type name
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'Описание операции',
            ],
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
