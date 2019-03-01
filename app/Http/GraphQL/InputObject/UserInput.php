<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'UserInput',
        'description' => 'Add new music group',
    ];

    public function fields()
    {
        return [
            'username' => [
                'name' => 'username',
                'description' => 'name',
                'type' => Type::string(),
                'rules' => ['required', 'string', 'max:255'],
            ],
            'email' => [
                'name' => 'email',
                'description' => 'name',
                'type' => Type::string(),
                'rules' => ['required', 'string', 'max:255', 'email', 'unique:users'],
            ],
            'password' => [
                'name' => 'password',
                'description' => 'name',
                'type' => Type::string(),
                'rules' => ['required', 'string', 'min:6'],
            ],
        ];
    }
}
