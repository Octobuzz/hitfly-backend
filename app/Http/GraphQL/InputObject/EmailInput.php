<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class EmailInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'EmailInput',
        'description' => 'email',
    ];

    public function fields()
    {
        return [
            'email' => [
                'name' => 'email',
                'description' => 'Пользовательский email',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['required', 'max:255', 'email', 'unique:users,email'],
            ],
        ];
    }
}
