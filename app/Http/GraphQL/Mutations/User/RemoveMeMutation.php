<?php

namespace App\Http\GraphQL\Mutations\User;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\User;
use Rebing\GraphQL\Support\Mutation;

class RemoveMeMutation extends Mutation
{
    use GraphQLAuthTrait;
    protected $attributes = [
        'name' => 'RemoveMeMutation',
        'description' => 'Удалить самого себя',
    ];

    public function type()
    {
        return \GraphQL::type('MyProfile');
    }

    public function resolve($root, $args)
    {
        /** @var User $user */
        $user = $this->getGuard()->user();

        $user->delete();

        return $user;
    }
}
