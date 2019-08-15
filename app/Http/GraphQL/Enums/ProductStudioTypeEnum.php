<?php

namespace App\Http\GraphQL\Enums;

use App\Models\Product;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProductStudioTypeEnum extends GraphQLType
{
    protected $enumObject = true;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $products = $this->getStudiaProducts();
        $this->attributes = [
            'name' => 'ProductStudioTypeEnum',
            'description' => 'Услуги студии(товары)',
            'values' => $products,
        ];
    }

    private function getStudiaProducts()
    {
        return Product::query()->select('alias')->where('alias', 'like', 'STUDIO_%')
            ->get()->pluck('alias', 'alias')->toArray();
    }
}
