<?php

namespace App\Http\GraphQL\Query;

use App\Models\Album;
use App\Models\Favourite;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Query\JoinClause;
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
        $query = Favourite::with($fields->getRelations());
        $query->select($fields->getSelect());
        $query->where('favourites.favouriteable_type', '=', Album::class);
        if (isset($args['albumId'])) {
            $query->where('favourites.favouriteable_id', $args['albumId']);
        }
        $query->leftJoin('albums', function (JoinClause $join) {
            $join->on('favourites.favouriteable_id', '=', 'albums.id');
        });
        $query->where('albums.deleted_at', '=', null);
        $query->where('albums.id', '<>', null);
        $query->where('favourites.user_id', \Auth::user()->id);

        //$query->orderBy('created_at', 'desc');
        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
