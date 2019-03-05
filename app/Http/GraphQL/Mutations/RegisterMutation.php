<?php

namespace App\Http\GraphQL\Mutations;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Rebing\GraphQL\Support\Mutation;

class RegisterMutation extends Mutation
{
    protected $attributes = [
        'name' => 'Register',
    ];

    public function type()
    {
        return \GraphQL::type('User');
    }

    public function args()
    {
        return [
            'user' => [
                'type' => \GraphQL::type('UserInput'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = User::query()->create([
            'username' => $args['user']['username'],
            'password' => Hash::make($args['user']['password']),
            'email' => $args['user']['email'],
            'gender' => $args['user']['gender'],
        ]);
        Auth::guard()->login($user);

        return $user;
    }
}
