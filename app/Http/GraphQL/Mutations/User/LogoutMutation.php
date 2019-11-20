<?php

namespace App\Http\GraphQL\Mutations\User;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class LogoutMutation extends Mutation
{
    use GraphQLAuthTrait;
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
        $this->getGuard()->logout();

        return true;
    }
}
