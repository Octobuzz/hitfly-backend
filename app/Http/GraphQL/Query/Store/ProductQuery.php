<?php

namespace App\Http\GraphQL\Query\Store;

use App\Models\Product;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

/**
 * Class ProductQuery.
 */
class ProductQuery extends Query
{
    protected $attributes = [
        'name' => 'ProductQuery',
        'description' => 'Товар',
    ];

    public function type()
    {
        return \GraphQL::type('ProductType');
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
            return Product::query()->where('id', $args['id'])->first();
        }

        return null;
    }
}
