<?php

namespace App\Http\GraphQL\Query;

use App\Models\Album;
use App\Models\Comment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class CommentsAlbumQuery extends Query
{
    protected $attributes = [
        'name' => 'Comment album Query',
    ];

    public function type()
    {
        return \GraphQL::paginate('CommentAlbum');
    }

    public function args()
    {
        return [
            'albumId' => ['name' => 'albumId', 'type' => Type::int()],
            'limit' => ['name' => 'limit', 'type' => Type::nonNull(Type::int())],
            'page' => ['name' => 'page', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        if (isset($args['albumId'])) {
            return Comment::with($fields->getRelations())->select($fields->getSelect())
                ->where('commentable_type', Album::class)
                ->where('commentable_id', $args['trackId'])
                ->paginate($args['limit'], ['*'], 'page', $args['page']);
        }

        return Comment::with($fields->getRelations())->select($fields->getSelect())
            ->where('commentable_type', Album::class)
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
