<?php

namespace App\Http\GraphQL\Query;

use App\Models\MusicGroup;
use App\Models\Watcheables;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class WatchingMusicGroupQuery extends Query
{
    protected $attributes = [
        'name' => 'WatchingMusicGroup',
        'description' => 'Следит за группой',
    ];

    public function type()
    {
        return \GraphQL::paginate('WatchableMusicGroupType');
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
            ->where('watcheable_type', MusicGroup::class)
            ->where('user_id', \Auth::user()->id)
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}