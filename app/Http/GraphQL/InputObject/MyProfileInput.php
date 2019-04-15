<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class MyProfileInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'MyProfileInput',
        'description' => 'Мой профиль',
    ];

    public function fields()
    {
        return [
            'username' => [
                'name' => 'username',
                'description' => 'Имя пользователя',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:250'],
            ],
            'cityId' => [
                'name' => 'cityId',
                'description' => 'ID города',
                'type' => Type::int(),
                'rules' => ['exists:cities,id'],
            ],
            'email' => [
                'name' => 'email',
                'description' => 'Email пользователя(для входа)',
                'type' => Type::string(),
                'rules' => ['max:250', 'email'],
            ],
            'password' => [
                'name' => 'password',
                'description' => 'Пароль',
                'type' => Type::string(),
                'rules' => ['min:6'],
            ],
            'genres' => [
                'name' => 'genres',
                'description' => 'любимые жанры пользователя',
                'type' => Type::listOf(Type::id()),
            ],
        ];
    }
}
