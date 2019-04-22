<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Genre;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
            'gender' => empty($args['user']['gender']) ? '' : $args['user']['gender'],
            'access_token' => Str::uuid(),
        ]);

        Auth::guard()->login($user);

        if (!empty($args['profile']['genres'])) {
            $tmpGenres = [];
            $genres = Genre::query()->findMany($args['user']['genres']);
            foreach ($genres as $genre) {
                $tmpGenres[] = $genre->id;
            }
            $user->favouriteGenres()->sync($tmpGenres);
        }

        return $user;
    }
}
