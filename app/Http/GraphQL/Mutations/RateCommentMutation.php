<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Comment;
use Rebing\GraphQL\Support\Mutation;

class RateCommentMutation extends Mutation
{
    use GraphQLAuthTrait;
    protected $attributes = [
        'name' => 'RateComment',
        'description' => 'Оценить отзыв',
    ];

    public function type()
    {
        return \GraphQL::type('CommentResult');
    }

    public function args()
    {
        return [
            'RateComment' => [
                'type' => \GraphQL::type('RateCommentInput'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $comment = Comment::find($args['RateComment']['commentId']);

        if (null === $comment) {
            return null;
        }

        $comment->estimation = $args['RateComment']['rate'];
        $comment->save();

        return $comment;
    }
}
