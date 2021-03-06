<?php

namespace App\Http\GraphQL\Mutations\User;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Hash;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;

class UpdatePasswordMutation extends Mutation
{
    use GraphQLAuthTrait;
    protected $attributes = [
        'name' => 'UpdateMyPasswoed',
        'description' => 'Обновление пароля текущего пользователя',
    ];

    public function type()
    {
        return \GraphQL::type('MyProfile');
    }

    public function args()
    {
        return [
            'password' => [
                'type' => Type::nonNull(\GraphQL::type('PasswordInput')),
                'description' => 'Пароль',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = $this->getGuard()->user();

        if (!empty($args['password']['password']) && $args['password']['password']) {
            $args['profile']['password'] = Hash::make($args['password']['password']);
        }
        if (empty($args['password']['oldPassword']) || !Hash::check($args['password']['oldPassword'], $user->password)) {
            return new ValidationError(trans('validation.oldPasswordIncorrect'));
        }
        if (!empty($args['password']['password']) && $args['password']['password']) {
            $user->password = Hash::make($args['password']['password']);
            $user->save();

            return $user;
        }

        return new ValidationError(trans('validation.emptyPassword'));
    }
}
