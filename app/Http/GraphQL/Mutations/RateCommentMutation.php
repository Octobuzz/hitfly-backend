<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;

class RateCommentMutation extends Mutation
{
    protected $attributes = [
        'name' => 'RateComment',
        'description' => 'Оценить отзыв',
    ];

    public function type()
    {
        return \GraphQL::type('CommentResult');
    }

    public function authorize(array $args)
    {
        return Auth::check();
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
