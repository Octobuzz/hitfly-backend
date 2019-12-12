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
                'type' => Type::string(),
                'rules' => ['max:250', 'sometimes', 'required'],
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
            'redirect' => [
                'name' => 'redirect',
                'description' => 'Редирект при смене email',
                'type' => \GraphQL::type('RedirectEnum'),
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
