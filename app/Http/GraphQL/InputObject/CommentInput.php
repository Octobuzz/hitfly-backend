<?php

namespace App\Http\GraphQL\InputObject;

use App\Rules\CriticOrStarComment;
use App\Rules\StarComment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CommentInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'CommentInput',
        'description' => 'Комментарий(отзыв)',
    ];

    public function fields()
    {
        return [
            'commentableId' => [
                'name' => 'commentableId',
                'description' => 'id комментируемого объекта(трек,альбом)',
                'type' => Type::nonNull(Type::int()),
                'rules' => ['required'],
            ],
            'commentableType' => [
                'name' => 'commentableType',
                'description' => 'Комментируемый тип',
                'type' => \GraphQL::type('CommentTypeEnum'),
                'rules' => ['required'],
            ],
            'comment' => [
                'name' => 'comment',
                'description' => 'Комментарий',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:250', new CriticOrStarComment(),'required'],
            ],
        ];
    }
}
