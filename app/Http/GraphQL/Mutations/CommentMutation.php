<?php

namespace App\Http\GraphQL\Mutations;

use App\Models\Album;
use App\Models\Track;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Models\Comment;


class CommentMutation
{
    /**
     * @param $rootValue
     * @param array $args
     * @return Comment
     * @throws \Exception
     */
    public function resolve($rootValue, array $args)
    {
        switch ($args['type']){
            case Comment::TYPE_ALBUM:
                $args['commentable_type'] = Album::class;
                $args['commentable_id'] = $args['id'];
                break;
            case Comment::TYPE_TRACK:
                $args['commentable_type'] = Track::class;
                $args['commentable_id'] = $args['id'];
                break;
            default:
                throw new \Exception('Не удалось определить тип комментария');
                break;
        }

        $args['user_id'] = Auth::user()->id;

        $comment = new Comment();
        foreach (Comment::FILLABLE as $fill) {
            $comment->$fill = $args[$fill];
        }
        $comment->save();

        return $comment;
    }


    /**
     * @param $rootValue
     * @param array $args
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
