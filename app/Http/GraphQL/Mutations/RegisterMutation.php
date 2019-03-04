<?php

namespace App\Http\GraphQL\Mutations;

use App\User;
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
            'info_track' => [
                'type' => \GraphQL::type('UserInput'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $user = new User();
        $user->username = $args['username'];
        $user->password = Hash::make($args['password']);
        $user->email = $args['email'];

        $user->save();

        return $user;
    }
}
