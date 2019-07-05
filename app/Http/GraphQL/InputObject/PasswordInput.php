<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PasswordInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'PasswordInput',
        'description' => 'пароль',
    ];

    public function fields()
    {
        return [
            'password' => [
                'name' => 'password',
                'description' => 'Пароль',
                'type' => Type::string(),
                'rules' => ['min:8', 'regex:/(?=^.{8,}$)(?=.*\d)(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*]).*$/i'],
            ],
            'confirmPassword' => [
                'name' => 'confirmPassword',
                'description' => 'Подтверждение пароля',
                'type' => Type::string(),
                'rules' => ['min:8', 'same:password.password'],
            ],
            'oldPassword' => [
                'name' => 'oldPassword',
                'description' => 'старый пароль',
                'type' => Type::string(),
                'rules' => ['min:3'],
            ],
        ];
    }
}
