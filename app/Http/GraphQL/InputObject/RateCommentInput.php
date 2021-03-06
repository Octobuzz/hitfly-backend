<?php

namespace App\Http\GraphQL\InputObject;

use App\Rules\AuthorRateComment;
use App\Rules\RateNotEstimatedComment;
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
                'description' => 'ID комментария',
                'type' => Type::nonNull(Type::int()),
                'rules' => ['max:250', new AuthorRateComment(), new RateNotEstimatedComment()],
            ],
            'rate' => [
                'name' => 'rate',
                'description' => 'Оценка',
                'type' => Type::nonNull(Type::int()),
                'rules' => ['max:250'],
            ],
        ];
    }
}
