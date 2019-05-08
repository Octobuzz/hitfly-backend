<?php

namespace App\Http\GraphQL\Query;

use App\Models\Album;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

/**
 * Class AlbumQuery.
 */
class AlbumsQuery extends Query
{
    protected $attributes = [
        'name' => 'Music Group Query',
        'description' => 'Список музыкальных альбомов',
    ];

    public function type()
    {
        return \GraphQL::paginate('Album');
    }

    public function args()
    {
        return [
            'limit' => ['name' => 'limit', 'type' => Type::nonNull(Type::int())],
            'page' => ['name' => 'page', 'type' => Type::nonNull(Type::int())],
            'my' => [
                'name' => 'my',
                'type' => Type::boolean(),
                'description' => 'Только мои альбомы',
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        if (false === empty($args['my']) && true === $args['my'] && null !== \Auth::user()) {
            return Album::with($fields->getRelations())
                ->where('user_id', '=', \Auth::user()->id)
                ->paginate($args['limit'], ['*'], 'page', $args['page']);
        }

        return Album::with($fields->getRelations())
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
