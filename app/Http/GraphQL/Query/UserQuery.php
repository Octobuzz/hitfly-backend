<?php

namespace App\Http\GraphQL\Query;

use App\User;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class UserQuery extends Query
{
    protected $attributes = [
        'name' => 'User query',
        'description' => 'Запрос пользователя системы',
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args, SelectFields $fields, GraphQL\Type\Definition\ResolveInfo $info)
    {
        return User::find($args['id']);
    }
}
