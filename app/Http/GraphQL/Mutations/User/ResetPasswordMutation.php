<?php

namespace App\Http\GraphQL\Mutations\User;

use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Http\Request;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class ResetPasswordMutation extends Mutation
{
    protected $attributes = [
        'name' => 'ResetPasswordMutation',
        'description' => 'Сброс пароля',
    ];

    public function type()
    {
        return Type::boolean();
    }

    public function args()
    {
        return [
            'email' => [
                'description' => 'Почта',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'email'],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $FP = new ForgotPasswordController();

        return $FP->sendResetLinkEmail(new Request($args));
    }
}
