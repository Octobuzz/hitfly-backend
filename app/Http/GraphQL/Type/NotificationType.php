<?php

namespace App\Http\GraphQL\Type;

use App\Models\Notification;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class NotificationType extends GraphQLType
{
    protected $attributes = [
        'name' => 'NotificationType',
        'model' => Notification::class,
        'description' => 'Нотификации',
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Id',
            ],
            'data' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'data',
            ],
            'readAt' => [
                'type' => Type::string(),
                'description' => 'date read',
                'alias' => 'read_at',
            ],

        ];
    }
}
