<?php

namespace App\Http\GraphQL\Query;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Favourite;
use App\Models\Lifehack;
use GraphQL\Type\Definition\Type;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class FavoriteLifehackQuery extends Query
{
    use GraphQLAuthTrait;

    protected $attributes = [
        'name' => 'Favorite life hack ',
        'description' => '',
    ];

    public function type()
    {
        return \GraphQL::paginate('FavouriteLifehack');
    }

    public function args()
    {
        return [
            'limit' => ['name' => 'limit', 'type' => Type::nonNull(Type::int())],
            'page' => ['name' => 'page', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args, SelectFields $fields): LengthAwarePaginator
    {
        $user = $this->getGuard()->user();
        $query = Favourite::with($fields->getRelations());
        $query->select($fields->getSelect());

        $query->where('favourites.favouriteable_type', '=', Lifehack::class);

        $query->where('favourites.user_id', $user->id);

        $query->orderBy('favourites.created_at', 'desc');

        return $query->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
