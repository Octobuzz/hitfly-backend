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
            'filters' => [
                'type' => \GraphQL::type('AlbumFilterInput'),
                'description' => 'Фильтры',
            ],

        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $query = Album::with($fields->getRelations());
        if (false === empty($args['filters']['my']) && true === $args['filters']['my'] && null !== \Auth::user()) {
            $query->where('user_id', '=', \Auth::user()->id);
        }
        if (false === empty($args['filters']['musicGroupId'])) {
            $query->where('music_group_id', '=', $args['filters']['musicGroupId']);
        }
        if (false === empty($args['filters']['userId'])) {
            $query->where('user_id', '=', $args['filters']['userId']);
        }
        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
