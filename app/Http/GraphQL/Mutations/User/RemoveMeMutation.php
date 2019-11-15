<?php

namespace App\Http\GraphQL\Mutations\User;

use App\User;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;

class RemoveMeMutation extends Mutation
{
    protected $attributes = [
        'name' => 'RemoveMeMutation',
        'description' => 'Удалить самого себя',
    ];

    public function type()
    {
        return \GraphQL::type('MyProfile');
    }

    public function authorize(array $args)
    {
        return Auth::guard('json')->check();
    }

    public function resolve($root, $args)
    {
        /** @var User $user */
        $user = Auth::guard('json')->user();

        $user->delete();

        return $user;
    }
}
