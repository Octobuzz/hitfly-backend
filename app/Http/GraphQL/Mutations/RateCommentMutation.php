<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Comment;
use GraphQL\Error\Error;
use Rebing\GraphQL\Support\Mutation;

class RateCommentMutation extends Mutation
{
    protected $attributes = [
        'name' => 'RateComment',
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
