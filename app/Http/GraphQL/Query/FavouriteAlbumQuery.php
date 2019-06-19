<?php

namespace App\Http\GraphQL\Query;

use App\Models\Album;
use App\Models\Favourite;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class FavouriteAlbumQuery extends Query
{
    protected $attributes = [
        'name' => 'Favourite album Query',
        'description' => 'Запрос пользователя системы',
    ];

    public function type()
    {
        return \GraphQL::paginate('FavouriteAlbum');
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
            return Favourite::with('favouriteable')
                ->where('favourites.favouriteable_type', Album::class)
                ->where('favourites.favouriteable_id', $args['albumId'])
                ->where('favourites.user_id', \Auth::user()->id)
                ->leftJoin('albums', function ($join) {
                    $join->on('favourites.favouriteable_id', '=', 'albums.id');
                })
                ->where('albums.deleted_at', '=', null)
                ->paginate($args['limit'], ['*'], 'page', $args['page']);
        }

        return Favourite::with('favouriteable')
            ->where('favourites.favouriteable_type', Album::class)
            ->where('favourites.user_id', \Auth::user()->id)
            ->leftJoin('albums', function ($join) {
                $join->on('favourites.favouriteable_id', '=', 'albums.id');
            })
            ->where('albums.deleted_at', '=', null)
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
