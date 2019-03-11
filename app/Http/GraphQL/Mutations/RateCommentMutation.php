<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Comment;
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
            'musicGroup' => [
                'type' => \GraphQL::type('RateCommentInput'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        var_dump($args);
        die();
        $comment = Comment::find($args['commentId']);

        if (null === $comment) {
            return null;
        }

        $comment->estimation = $args['rate'];
        $comment->save();

        return $comment;
    }
}
