<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 28.02.19
 * Time: 13:49.
 */

namespace App\Http\GraphQL\Query;

use App\Models\MusicGroup;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class MusicGroupQuery extends Query
{
    protected $attributes = [
        'name' => 'Music Group Query',
    ];

    public function type()
    {
        return Type::listOf(\GraphQL::type('MusicGroup'));
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
            return MusicGroup::where('id', $args['id'])->get();
        }

        return MusicGroup::all();
    }
}
