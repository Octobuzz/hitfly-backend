<?php

namespace App\Http\GraphQL\Query;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Collection;
use App\Models\Favourite;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Query\JoinClause;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class FavouriteCollectionQuery extends Query
{
    use  GraphQLAuthTrait;
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
        $user = $this->getGuard()->user();
        if (null === $user) {
            return null;
        }
        $query = Favourite::with($fields->getRelations());
        $query->select($fields->getSelect());
        $query->where('favourites.favouriteable_type', '=', Collection::class);
        $query->where('favourites.user_id', $user->id);
        if (isset($args['collectionId'])) {
            $query->where('favourites.favouriteable_id', $args['collectionId']);
        }
        $query->leftJoin('collections', function (JoinClause $join) {
            $join->on('favourites.favouriteable_id', '=', 'collections.id');
        });
        $query->where('collections.is_admin', '=', 0);

        $query->orderBy('favourites.created_at', 'desc');
        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
