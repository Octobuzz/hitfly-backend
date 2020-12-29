<?php

namespace App\Http\GraphQL\Query;

use App\Models\MusicGroup;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class MusicGroupQuery extends Query
{
    protected $attributes = [
        'name' => 'Music Group Query',
        'description' => 'Запрос музыкальной группы',
    ];

    public function type()
    {
        return \GraphQL::type('MusicGroup');
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::int())],
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['id'])) {
            return MusicGroup::query()->where('id', $args['id'])->first();
        }

        return null;
    }
}
