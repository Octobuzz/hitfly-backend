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
        'description' => 'Отзыв',
    ];

    public function fields()
    {
        return [
            'commentableId' => [
                'name' => 'commentableId',
                'description' => 'id  объекта для отзыва(трек,альбом)',
                'type' => Type::nonNull(Type::int()),
                'rules' => ['required'],
            ],
            'commentableType' => [
                'name' => 'commentableType',
                'description' => 'Тип для отзыва',
                'type' => \GraphQL::type('CommentTypeEnum'),
                'rules' => ['required'],
            ],
            'comment' => [
                'name' => 'comment',
                'description' => 'Отзыв',
                'type' => Type::nonNull(Type::string()),
                'rules' => ['max:250', new CriticOrStarComment(), 'required'],
            ],
            'orderId' => [
                'name' => 'orderId',
                'description' => 'id Заказа',
                'type' => Type::int(),
                'rules' => [new StarComment()],
            ],
        ];
    }
}
