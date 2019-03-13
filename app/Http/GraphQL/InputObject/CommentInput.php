<?php

namespace App\Http\GraphQL\InputObject;

use App\Rules\CriticComment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CommentInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'CommentInput',
        'description' => 'Add new music group',
    ];

    public function fields()
    {
        return [
            'commentableId' => [
                'name' => 'commentableId',
                'description' => 'name',
                'type' => Type::nonNull(Type::int()),
                'rules' => ['max:250'],
            ],
            'commentableType' => [
                'name' => 'commentableType',
                'description' => 'name',
                'type' => \GraphQL::type('CommentType'),
                'rules' => ['max:250'],
            ],
            'comment' => [
                'name' => 'comment',
                'description' => 'name',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:250',new CriticComment()],
            ],
        ];
    }
}
