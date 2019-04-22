<?php

namespace App\Http\GraphQL\Query;

use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

/**
 * Class AlbumQuery.
 */
class TracksQuery extends Query
{
    protected $attributes = [
        'name' => 'Music Group Query',
        'description' => 'Запрос треков',
    ];

    public function type()
    {
        return \GraphQL::paginate('Track');
    }

    public function args()
    {
        return [
            'limit' => ['name' => 'limit', 'type' => Type::int()],
            'page' => ['name' => 'page', 'type' => Type::int()],
            'my' => [
                'name' => 'my',
                'type' => Type::boolean(),
                'description' => 'Только мои треки',
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        if (false === empty($args['my']) && true === $args['my'] && null !== \Auth::user()) {
            return Track::with($fields->getRelations())
                ->where('user_id', '=', \Auth::user()->id)
                ->paginate($args['limit'], ['*'], 'page', $args['page']);
        }

        return Track::with($fields->getRelations())
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
