<?php

namespace App\Http\GraphQL\Query;

use App\Models\Favourite;
use App\Models\Track;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class FavouriteTrackQuery extends Query
{
    protected $attributes = [
        'name' => 'Favourite track Query',
        'description' => 'Запрос любимых треков',
    ];

    public function type()
    {
        return \GraphQL::paginate('FavouriteTrack');
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
            return Favourite::with('favouriteable')
                ->leftJoin('tracks', function ($join) {
                    $join->on('favourites.favouriteable_id', '=', 'tracks.id');
                })
                    ->where('tracks.deleted_at', '=', null)
                ->where('favourites.favouriteable_type', Track::class)
                ->where('favourites.favouriteable_id', $args['trackId'])
                ->where('favourites.user_id', \Auth::user()->id)

                ->paginate($args['limit'], ['*'], 'page', $args['page']);
        }

        return Favourite::with('favouriteable')
            ->where('favourites.favouriteable_type', Track::class)
            ->where('favourites.user_id', \Auth::user()->id)
            ->leftJoin('tracks', function ($join) {
                $join->on('favourites.favouriteable_id', '=', 'tracks.id');
            })
            ->where('tracks.deleted_at', '=', null)
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
