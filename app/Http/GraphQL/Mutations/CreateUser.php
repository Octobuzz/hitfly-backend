<?php

namespace App\Http\GraphQL\Mutations;

use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateUser
{
    /**
     * Return a value for the field.
     *
     * @param null                $rootValue   Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param array               $args        the arguments that were passed into the field
     * @param GraphQLContext|null $context     arbitrary data that is shared between all fields of a single query
     * @param ResolveInfo         $resolveInfo information about the query itself, such as the execution state, the field name, path to the field from the root, and more
     *
     * @return mixed
     */
    public function register($rootValue, array $args, $context = null, $resolveInfo)
    {
        $validator = Validator::make($args, [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return null;
        }
        $user = new User();
        $user->username = $args['username'];
        $user->password = Hash::make($args['password']);
        $user->email = $args['email'];

        $user->save();

        return $user;
    }
}
