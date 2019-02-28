<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 28.02.19
 * Time: 16:27.
 */

namespace App\Http\GraphQL\Query;

use App\Models\Comment;
use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class CommentTrackQuery extends Query
{
    protected $attributes = [
        'name' => 'Comment track Query',
    ];

    public function type()
    {
        return Type::listOf(\GraphQL::type('CommentTrack'));
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::int()],
            'trackId' => ['name' => 'trackId', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['id'])) {
            return Comment::where('id', $args['id'])->where('commentable_type', Track::class)->get();
        }

        if (isset($args['trackId'])) {
            return Comment::where('commentable_id', $args['trackId'])->where('commentable_type', Track::class)->get();
        }

        return Comment::where('commentable_type', Track::class)->get();
    }
}
