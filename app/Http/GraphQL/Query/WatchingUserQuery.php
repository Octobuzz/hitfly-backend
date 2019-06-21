<?php

namespace App\Http\GraphQL\Query;

use App\Models\Watcheables;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class WatchingUserQuery extends Query
{
    protected $attributes = [
        'name' => 'WatchingUser',
        'description' => 'Следит за пользователями',
    ];

    public function type()
    {
        return \GraphQL::paginate('WatchableUserType');
    }

    public function args()
    {
        return [
            'limit' => ['name' => 'limit', 'type' => Type::nonNull(Type::int())],
            'page' => ['name' => 'page', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
//        $query = new User();
//        $response = $query->watchingUser()->paginate($args['limit'], ['*'], 'page', $args['page']);
//
//        return $response;
        return Watcheables::with('watcheable')
            ->where('watcheable_type', User::class)
            ->where('user_id', \Auth::user()->id)
            ->leftJoin('users', function ($join) {
                $join->on('watcheables.watcheable_id', '=', 'users.id');
            })
            ->where('users.deleted_at', '=', null)
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
