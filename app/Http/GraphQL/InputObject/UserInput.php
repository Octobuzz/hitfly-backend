<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'UserInput',
        'description' => 'Add new music group',
    ];

    public function fields()
    {
        return [
            'username' => [
                'name' => 'username',
                'description' => 'Имя пользователя',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'string', 'max:255'],
            ],
//            'email' => [
//                'name' => 'email',
//                'description' => 'Пользовательский email',
//                'type' => Type::nonNull(Type::string()),
//                'rules' => ['required', 'max:255', 'email', 'unique:users,email'],
//            ],
//            'password' => [
//                'name' => 'password',
//                'description' => 'Пароль для пользователя',
//                'type' => Type::nonNull(Type::string()),
//                'rules' => ['required', 'string', 'min:6'],
//            ],
            'gender' => [
                'name' => 'gender',
                'description' => 'Пол пользователя',
                'type' => \GraphQL::type('GenderType'),
                'rules' => ['nullable'],
            ],
            'genres' => [
                'name' => 'genres',
                'description' => 'Любимые жанры пользователя',
                'type' => Type::listOf(Type::id()),
            ],
        ];
    }
}
