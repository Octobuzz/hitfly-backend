<?php

namespace App\Http\GraphQL\Query;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Favourite;
use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Query\JoinClause;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class FavouriteTrackQuery extends Query
{
    use GraphQLAuthTrait;
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
        $user = $this->getGuard()->user();
        if (null === $user) {
            return null;
        }

        $query = Favourite::query();
        $query->select($fields->getSelect());
        $query->where('favourites.favouriteable_type', '=', Track::class);
        if (isset($args['trackId'])) {
            $query->where('favourites.favouriteable_id', $args['trackId']);
        }
        $query->leftJoin('tracks', function (JoinClause $join) {
            $join->on('favourites.favouriteable_id', '=', 'tracks.id');
        });
        $query->where('tracks.deleted_at', '=', null);
        $query->where('tracks.id', '<>', null);
        $query->where('tracks.state', '=', 'published');
        $query->where('favourites.user_id', $user->id);

        //$query->orderBy('created_at', 'desc');
        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
