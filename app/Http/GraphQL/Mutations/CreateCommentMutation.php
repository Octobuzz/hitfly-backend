<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Album;
use App\Models\Comment;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;

class CreateCommentMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateComment',
        'description' => 'Создать отзыв',
    ];

    public function type()
    {
        return \GraphQL::type('CommentResult');
    }

    public function args()
    {
        return [
            'Comment' => [
                'type' => \GraphQL::type('CommentInput'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        switch ($args['Comment']['commentableType']) {
            case Comment::TYPE_TRACK:
                $class = Track::class;
                break;
            case Comment::TYPE_ALBUM:
                $class = Album::class;
                break;
            default:
                throw new \Exception('Не удалось определить тип комментария');
        }
        $comment = new  Comment();
        $comment->comment = $args['Comment']['comment'];
        $comment->commentable_type = $class;
        $comment->commentable_id = $args['Comment']['commentableId'];
        $comment->user_id = Auth::user()->id;
        $comment->save();

        return $comment;
    }
}
