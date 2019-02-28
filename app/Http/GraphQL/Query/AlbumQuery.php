<?php

namespace App\Http\GraphQL\Query;

use App\Models\Album;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

/**
 * Class AlbumQuery.
 */
class AlbumQuery extends Query
{
    protected $attributes = [
        'name' => 'Music Group Query',
    ];

    public function type()
    {
        return Type::listOf(\GraphQL::type('Album'));
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['id'])) {
            return Album::where('id', $args['id'])->get();
        }

        return Album::all();
    }
}
