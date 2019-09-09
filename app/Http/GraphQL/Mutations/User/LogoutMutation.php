<?php

namespace App\Http\GraphQL\Mutations\User;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
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

    public function resolve($root, $args)
    {
        $login = new LoginController();

        //return $login->logout(new Request($args));
        Auth::guard('json')->logout();

        return true;
    }
}