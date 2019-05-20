<?php

namespace App\Http\GraphQL\Query;

use App\Models\Collection;
use App\Models\Favourite;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class FavouriteCollectionQuery extends Query
{
    protected $attributes = [
        'name' => 'Favourite Collection Query',
        'description' => 'Запрос любимых плейлистов',
    ];

    public function type()
    {
        return \GraphQL::paginate('FavouriteCollection');
    }

    public function args()
    {
        return [
            'collectionId' => ['name' => 'collectionId', 'type' => Type::int()],
            'limit' => ['name' => 'limit', 'type' => Type::nonNull(Type::int())],
            'page' => ['name' => 'page', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        if (isset($args['collectionId'])) {
            return Favourite::with('favouriteable')
                ->where('favouriteable_type', Collection::class)
                ->where('favouriteable_id', $args['collectionId'])
                ->where('user_id', \Auth::user()->id)
                ->leftJoin('collections', function ($join) {
                    $join->on('favourites.favouriteable_id', '=', 'collections.id');
                })
                ->where('collections.is_admin', '=', 0)
                ->paginate($args['limit'], ['*'], 'page', $args['page']);
        }

        return Favourite::with('favouriteable')
            ->where('favouriteable_type', Collection::class)
            ->where('user_id', \Auth::user()->id)
            ->leftJoin('collections', function ($join) {
                $join->on('favourites.favouriteable_id', '=', 'collections.id');
            })
            ->where('collections.is_admin', '=', 0)
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
