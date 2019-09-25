<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class GroupMembersInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'GroupMembersInput',
        'description' => 'Приглашенные в группу',
    ];

    public function fields()
    {
        return [
            'email' => [
                'name' => 'email',
                'description' => 'email',
                'type' => Type::string(),
                'rules' => ['sometimes', 'required_without:user_id', 'email'],
            ],
            'user_id' => [
                'name' => 'user_id',
                'description' => 'id пользователя(приглашаемого)',
                'type' => Type::int(),
                'rules' => ['sometimes', 'required_without:email'],
            ],
        ];
    }
}
