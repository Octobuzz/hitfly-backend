<?php

namespace App\Http\GraphQL\Mutations\User;

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Validation\Rule;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class RegistrationMutation extends Mutation
{
    protected $attributes = [
        'name' => 'RegistrationMutation',
        'description' => 'Регистрация',
    ];

    public function type()
    {
        return \GraphQL::type('User');
    }

    public function args()
    {
        return [
            'email' => [
                'description' => 'Почта',
                'type' => Type::string(),
                'rules' => ['required', 'email',  'unique:users'],
            ],
            'password' => [
                'description' => 'Пароль',
                'type' => Type::string(),
                'rules' => ['required', 'min:8', 'regex:/(?=^.{8,}$)(?=.*\d)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/i'],
            ],
//            'password_confirmation' => [
//                'description' => 'Подтверждение пароля',
//                'type' => Type::string(),
//                'rules' => ['required'],
//            ],
            'birthday' => [
                'description' => 'День рождения',
                'type' => Type::string(),
                'rules' => ['nullable', 'date', 'before:today'],
            ],

            'gender' => [
                'description' => 'День рождения',
                'type' => \GraphQL::type('GenderType'),
                'rules' => ['nullable', Rule::in(['M', 'F'])],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $register = new RegisterController();

        return $register->registerAPI($args);
    }
}
