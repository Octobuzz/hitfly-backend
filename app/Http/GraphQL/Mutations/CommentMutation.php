<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Album;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;

class CommentMutation
{
    /**
     * @param $rootValue
     * @param array $args
     *
     * @return Comment
     *
     * @throws \Exception
     */
    public function resolve($rootValue, array $args)
    {
        switch ($args['type']) {
                case Comment::TYPE_ALBUM:
                    $args['commentable_type'] = Album::class;
                    break;
                case Comment::TYPE_TRACK:
                    $args['commentable_type'] = Track::class;
                    break;
                default:
                    throw new \Exception('Не удалось определить тип комментария');
                    break;
            }

        $args['commentable_id'] = $args['id'];
        $args['user_id'] = Auth::user()->id;

        $comment = Comment::updateOrCreate(['id' => $args['commentId']], $args);

        $comment->save();

        return $comment;
    }

    /**
     * @param $rootValue
     * @param array $args
     *
     * @return Comment
     */
    public function rateComment($rootValue, array $args)
    {
        $comment = Comment::find($args['commentId']);
        $comment->estimation = $args['rate'];
        $comment->save();

        return $comment;
    }
}
