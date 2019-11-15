<?php

namespace App\Http\GraphQL\Mutations\User;

use Illuminate\Support\Facades\Auth;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class LogoutMutation extends Mutation
{
    protected $attributes = [
        'name' => 'LogoutMutation',
        'description' => 'Logout',
    ];

    public function type()
    {
        return Type::boolean();
    }

    public function authorize(array $args)
    {
        return Auth::guard('json')->check();
    }

    public function resolve($root, $args)
    {
        Auth::logout();

        return true;
    }
}
