<?php

namespace App\Http\GraphQL\Query;

use App\Helpers\DBHelpers;
use App\Models\Album;
use App\Models\Comment;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class CommentsAlbumQuery extends Query
{
    protected $attributes = [
        'name' => 'Comment album Query',
        'description' => 'Отзывы к альбому',
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
            'commentPeriod' => [
                'type' => \GraphQL::type('CommentPeriodEnum'),
                'description' => 'Фильтрация комментов по периоду',
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $query = Comment::with($fields->getRelations());

        $query->select($fields->getSelect());
        $query->where('commentable_type', Album::class);
        if (isset($args['albumId'])) {
            $query->where('commentable_id', $args['albumId']);
        }

        if (false === empty($args['commentPeriod'])) {
            $date = DBHelpers::getPeriod($args['commentPeriod']);
            $query->where('created_at', '>=', $date);
        }
        $query->orderBy('created_at', 'desc');
        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
