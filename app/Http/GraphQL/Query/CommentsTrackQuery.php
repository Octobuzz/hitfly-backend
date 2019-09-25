<?php

namespace App\Http\GraphQL\Query;

use App\Helpers\DBHelpers;
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
            'filters' => [
                'type' => \GraphQL::type('CommentsTrackFilterInput'),
                'description' => 'Фильтры',
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $query = Comment::with($fields->getRelations());

        if (false === empty($args['filters']['commentPeriod'])) {
            $date = DBHelpers::getPeriod($args['filters']['commentPeriod']);
            $query->where('created_at', '>=', $date);
        }
        //$query->select($fields->getSelect());
        $query->where('commentable_type', Track::class);
        if (isset($args['trackId'])) {
            $query->where('commentable_id', $args['trackId']);
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
