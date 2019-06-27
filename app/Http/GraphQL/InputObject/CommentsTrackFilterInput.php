<?php

namespace App\Http\GraphQL\InputObject;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CommentsTrackFilterInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'CommentsTrackFilterInput',
        'description' => 'Фильтры для отзывов к треку',
    ];

    public function fields()
    {
        return [
            'commentPeriod' => [
                'type' => \GraphQL::type('CommentPeriodEnum'),
                'description' => 'Фильтрация по периоду комментирования',
            ],
        ];
    }
}
