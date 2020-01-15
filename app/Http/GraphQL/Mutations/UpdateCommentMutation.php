<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Album;
use App\Models\Comment;
use App\Models\Track;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class UpdateCommentMutation extends Mutation
{
    use GraphQLAuthTrait;
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
                'description' => 'ID комментария',
            ],
        ];
    }

    public function rules(array $args = [])
    {
        return [
           'id' => [function ($attribute, $value, $fail) {
               $comment = Comment::query()->find($value);

               if (null === $comment) {
                   return $fail(__('validation.editCommentNotExist', ['id' => $value]));
               }
               // die(json_encode($comment->created_at));
               $commentCreated = Carbon::createFromTimeString($comment->created_at);
               $now = Carbon::now();
               $diff_in_hours = $commentCreated->diffInHours($now);
               if ((int) config('buisness.comment_edit', 5) < $diff_in_hours) {
                   $fail(__('validation.editCommentTime', ['hours' => config('buisness.comment_edit')]));
               }
           },
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
                throw new \InvalidArgumentException('Не удалось определить тип комментария');
        }
        $comment = Comment::query()->find($args['id']);
        $comment->comment = $args['Comment']['comment'];
        $comment->commentable_type = $class;
        $comment->commentable_id = $args['Comment']['commentableId'];
        $comment->user_id = $this->getGuard()->user()->id;
        $comment->save();

        return $comment;
    }
}
