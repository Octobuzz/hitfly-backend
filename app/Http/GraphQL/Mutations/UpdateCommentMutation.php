<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Album;
use App\Models\Comment;
use App\Models\Track;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Rebing\GraphQL\Support\Mutation;

class UpdateCommentMutation extends Mutation
{
    protected $attributes = [
        'name' => 'CreateComment',
        'description' => 'Изменение комментария(отзыва)',
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
            'id' => [
                'type' => Type::int(),
                'description' => 'ID комментария'
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $comment = Comment::query()->find($args['id']);
       // die(json_encode($comment->created_at));
        $commentCreated = Carbon::createFromTimeString($comment->created_at);
        $now = Carbon::now();
        $diff_in_hours = $commentCreated->diffInHours($now);

        die(var_dump($diff_in_hours));

        return $v;
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
