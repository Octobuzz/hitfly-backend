<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Album;
use App\Models\Comment;
use App\Models\Track;
use Rebing\GraphQL\Support\Mutation;

class CreateCommentMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateComment',
    ];

    public function type()
    {
        return \GraphQL::type('CommentResult');
    }

    public function args()
    {
        return [
            'musicGroup' => [
                'type' => \GraphQL::type('CommentInput'),
            ],
        ];
    }

    public function resolve($root, $args)
    {
        switch ($args['commentableType']) {
            case Comment::TYPE_TRACK:
                $class = Track::class;
                break;
            case Comment::TYPE_ALBUM:
                $class = Album::class;
                break;
            default:
                throw new \Exception('Не удалось определить тип комментария');
        }

        $comment = Comment::create($args);
        $comment['commentable_type'] = $class;
        $comment->save();

        return $comment;
    }
}
