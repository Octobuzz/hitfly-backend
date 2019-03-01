<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class RateCommentInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'RateCommentInput',
        'description' => '',
    ];

    public function fields()
    {
        return [
            'commentId' => [
                'name' => 'commentId',
                'description' => 'name',
                'type' => Type::nonNull(Type::int()),
                'rules' => ['max:250'],
            ],
            'rate' => [
                'name' => 'rate',
                'description' => 'name',
                'type' => Type::nonNull(Type::int()),
                'rules' => ['max:250'],
            ],
        ];
    }
}
