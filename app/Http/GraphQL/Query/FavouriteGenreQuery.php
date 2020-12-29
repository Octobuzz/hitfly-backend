<?php

namespace App\Http\GraphQL\Query;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Favourite;
use App\Models\Genre;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class FavouriteGenreQuery extends Query
{
    use  GraphQLAuthTrait;
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
        $user = $this->getGuard()->user();

        $query = Favourite::with($fields->getRelations());
        $query->select($fields->getSelect());
        $query->where('favouriteable_type', '=', Genre::class);
        if (isset($args['genreId'])) {
            $query->where('favouriteable_id', $args['genreId']);
        }
        $query->where('user_id', $user->id);
        $query->orderBy('favourites.created_at', 'desc');
        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
