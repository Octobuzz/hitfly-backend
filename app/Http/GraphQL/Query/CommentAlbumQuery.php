<?php

namespace App\Http\GraphQL\Query;

use App\Models\Album;
use App\Models\Comment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class CommentAlbumQuery extends Query
{
    protected $attributes = [
        'name' => 'Comment album Query',
    ];

    public function type()
    {
        return Type::listOf(\GraphQL::type('CommentAlbum'));
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::int()],
            'albumId' => ['name' => 'albumId', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['id'])) {
            return Comment::where('id', $args['id'])->where('commentable_type', Album::class)->get();
        }

        if (isset($args['albumId'])) {
            return Comment::where('commentable_id', $args['albumId'])->where('commentable_type', Album::class)->get();
        }

        return Comment::where('commentable_type', Album::class)->get();
    }
}
