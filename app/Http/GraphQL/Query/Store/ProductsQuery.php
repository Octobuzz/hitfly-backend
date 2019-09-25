<?php

namespace App\Http\GraphQL\Query\Store;

use App\Models\Product;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

/**
 * Class AlbumQuery.
 */
class ProductsQuery extends Query
{
    protected $attributes = [
        'name' => 'ProductsQuery',
        'description' => 'Список товаров',
    ];

    public function type()
    {
        return \GraphQL::paginate('ProductType');
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
        $query = Product::with($fields->getRelations());
        $response = $query->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }
}
