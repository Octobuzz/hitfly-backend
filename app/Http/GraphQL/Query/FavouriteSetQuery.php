<?php

namespace App\Http\GraphQL\Query;

use App\Models\Collection;
use App\Models\Favourite;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class FavouriteSetQuery extends Query
{
    protected $attributes = [
        'name' => 'Favourite Collection Query',
        'description' => 'Запрос любимых подборок',
    ];

    public function type()
    {
        return \GraphQL::paginate('FavouriteCollection');
    }

    public function args()
    {
        return [
            'setId' => ['name' => 'setId', 'type' => Type::int()],
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
        $query->where('favourites.favouriteable_type', '=', Collection::class);
        $query->where('favourites.user_id', $user->id);
        if (isset($args['collectionId'])) {
            $query->where('favourites.favouriteable_id', $args['setId']);
        }
        $query->leftJoin('collections', function (JoinClause $join) {
            $join->on('favourites.favouriteable_id', '=', 'collections.id');
        });
        $query->where('collections.is_admin', '=', 1);

        //$query->orderBy('created_at', 'desc');
        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
