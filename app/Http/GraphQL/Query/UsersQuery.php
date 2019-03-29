<?php

namespace App\Http\GraphQL\Query;

use App\User;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'Users query',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('User'));
    }

    public function resolve($root, $args)
    {
        return User::all();
    }
}
