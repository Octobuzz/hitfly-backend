<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.03.19
 * Time: 14:58.
 */

namespace App\Http\GraphQL\Query;

use App\Models\Track;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class CollectionQuery extends Query
{
    protected $attributes = [
        'name' => 'Collection Query',
    ];

    public function type()
    {
        return \GraphQL::type('Collection');
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
            return Track::query()->where('id', $args['id'])->first();
        }

        return null;
    }
}
