<?php

namespace App\Http\GraphQL\Type;

use App\Http\GraphQL\Privacy\UserPrivacy;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'Пользователь системы',
        'model' => User::class,
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the user',
                'alias' => 'user_id', // Use 'alias', if the database column is different from the type name
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of user',
                'privacy' => UserPrivacy::class,
            ],
            'username' => [
                'type' => Type::string(),
                'description' => 'The email of user',
            ],
            'accessToken' => [
                'type' => Type::string(),
                'description' => 'The access token',
                'alias' => 'access_token',
                'resolve' => function ($model) {
                    return $model->access_token;
                },
            ],
            // Uses the 'getIsMeAttribute' function on our custom User model
            'isMe' => [
                'type' => Type::boolean(),
                'description' => 'True, if the queried user is the current user',
                'selectable' => false, // Does not try to query this from the database
            ],
        ];
    }
}
