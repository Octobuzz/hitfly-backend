<?php

namespace App\Http\GraphQL\Query;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\MusicGroup;
use App\Models\Watcheables;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class WatchingMusicGroupQuery extends Query
{
    use GraphQLAuthTrait;
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
        $user = $this->getGuard()->user();

        return Watcheables::with('watcheable')
            ->where('watcheable_type', MusicGroup::class)
            ->where('user_id', $user->id)
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
