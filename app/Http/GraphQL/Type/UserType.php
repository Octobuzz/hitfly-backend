<?php

namespace App\Http\GraphQL\Type;

use App\User;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'Пользователь системы',
        'model' => User::class,
    ];

    public function fields()
    {
        $interface = \GraphQL::type('UserInterface');

        return $interface->getFields();
    }
}
