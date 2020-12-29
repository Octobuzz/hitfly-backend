<?php

namespace App\Http\GraphQL\Query;

use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

/**
 * Class AlbumQuery.
 */
class TracksByIdsQuery extends Query
{
    protected $attributes = [
        'name' => 'TracksByIdsQuery',
        'description' => 'Запрос треков по ID',
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
            'ids' => ['name' => 'ids', 'type' => Type::listOf(Type::int())],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        if ($args['limit'] > 100) {
            $args['limit'] = 100;
        }

        $query = Track::with($fields->getRelations());
        $query->whereIn('id', $args['ids']);
        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
