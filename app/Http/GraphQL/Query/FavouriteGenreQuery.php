<?php

namespace App\Http\GraphQL\Query;

use App\Models\Favourite;
use App\Models\Genre;
use App\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        if (null === $user) {
            return null;
        }
        $query = Favourite::with($fields->getRelations());
        $query->select($fields->getSelect());
        $query->where('favouriteable_type', '=', Genre::class);
        if (isset($args['genreId'])) {
            $query->where('favouriteable_id', $args['genreId']);
        }
        $query->where('user_id', $user->id);
        //$query->orderBy('created_at', 'desc');
        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
