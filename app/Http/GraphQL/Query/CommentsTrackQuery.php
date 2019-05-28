<?php

namespace App\Http\GraphQL\Query;

use App\Models\Comment;
use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class CommentsTrackQuery extends Query
{
    protected $attributes = [
        'name' => 'Comment track Query',
        'description' => 'Отзывы к треку',
    ];

    public function type()
    {
        return \GraphQL::paginate('CommentTrack');
    }

    public function args()
    {
        return [
            'trackId' => ['name' => 'trackId', 'type' => Type::int()],
            'limit' => ['name' => 'limit', 'type' => Type::nonNull(Type::int())],
            'page' => ['name' => 'page', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        if (isset($args['trackId'])) {
            return Comment::with($fields->getRelations())->select($fields->getSelect())
                ->where('commentable_type', Track::class)
                ->where('commentable_id', $args['trackId'])
                ->paginate($args['limit'], ['*'], 'page', $args['page']);
        }

        return Comment::with($fields->getRelations())->select($fields->getSelect())
            ->where('commentable_type', Track::class)
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
