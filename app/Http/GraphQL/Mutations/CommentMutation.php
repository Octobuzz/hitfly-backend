<?php

namespace App\Http\GraphQL\Mutations;

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
            case "album":
                $args['commentable_type'] = 'App\Models\Album';
                $args['commentable_id'] = $args['id'];
                break;
            case "track":
                $args['commentable_type'] = 'App\Models\Track';
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
