<?php

namespace App\Http\GraphQL\Mutations\User;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class LoginMutation extends Mutation
{
    protected $attributes = [
        'name' => 'LoginMutation',
        'description' => 'Авторизация',
    ];

    public function type()
    {
        return Type::nonNull(\GraphQL::type('User'));
    }

    public function args()
    {
        return [
            'email' => [
                'description' => 'Почта',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'email', 'login_password_correct'],
            ],
            'password' => [
                'description' => 'Пароль',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'login_password_correct'],
            ],
            'remember' => [
                'description' => 'Запомнить меня',
                'type' => Type::boolean(),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $login = new LoginController();
        $user = $login->loginApi(new Request($args));

        return $user;
    }
}
