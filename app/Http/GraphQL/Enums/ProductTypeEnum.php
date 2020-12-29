<?php

namespace App\Http\GraphQL\Enums;

use App\Models\Product;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProductTypeEnum extends GraphQLType
{
    protected $enumObject = true;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $products = $this->getStudiaProducts();
        $this->attributes = [
            'name' => 'ProductTypeEnum',
            'description' => 'Товары',
            'values' => $products,
        ];
    }

    private function getStudiaProducts()
    {
        return Product::query()->select('alias')
            ->get()->pluck('alias', 'alias')->toArray();
    }
}
