<?php

namespace App\Http\GraphQL\Query;

use App\Models\MusicGroup;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class MusicGroupsQuery extends Query
{
    protected $attributes = [
        'name' => 'Music Group Query',
    ];

    public function type()
    {
        return \GraphQL::paginate('MusicGroup');
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
        return MusicGroup::with($fields->getRelations())
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
