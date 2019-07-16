<?php

namespace App\Http\GraphQL\Query;

use App\Models\Favourite;
use App\Models\Track;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class FavouriteGenreQuery extends Query
{
    protected $attributes = [
        'name' => 'Избранные жанры',
        'description' => 'Запрос любимых жанров',
    ];

    public function type()
    {
        return \GraphQL::paginate('FavouriteGenre');
    }

    public function args()
    {
        return [
            'genreId' => ['name' => 'genreId', 'type' => Type::int()],
            'limit' => ['name' => 'limit', 'type' => Type::nonNull(Type::int())],
            'page' => ['name' => 'page', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        if (isset($args['genreId'])) {
            return Favourite::with('favouriteable')
                ->where('favouriteable_type', Track::class)
                ->where('favouriteable_id', $args['genreId'])
                ->where('user_id', \Auth::user()->id)
                ->paginate($args['limit'], ['*'], 'page', $args['page']);
        }

        return Favourite::with('favouriteable')
            ->where('favouriteable_type', Track::class)
            ->where('user_id', \Auth::user()->id)
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
