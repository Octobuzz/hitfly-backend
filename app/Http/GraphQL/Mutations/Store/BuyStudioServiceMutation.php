<?php

namespace App\Http\GraphQL\Mutations\Store;

use App\Models\Product;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;

class BuyStudioServiceMutation extends Mutation
{
    private $order;

    public function __construct($attributes = [], \App\BuisnessLogic\Orders\Order $order)
    {
        parent::__construct($attributes);
        $this->order = $order;
    }

    protected $attributes = [
        'name' => 'BuyStudioMutation',
        'description' => 'Покупка услуг студии',
    ];

    public function type()
    {
        return \GraphQL::type('OperationType');
    }

    public function args()
    {
        return [
            'studioProduct' => [
                'type' => Type::nonNull(\GraphQL::type('ProductStudioTypeEnum')),
                'description' => 'Товар Услуга на студии',
                'rules' => 'required',
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $product = Product::query()->where('alias', $args['studioProduct'])->first();
        if (null === $product) {
            return  new ValidationError('Нет такого товара');
        }

        return $this->order->buyProduct($product);
    }
}
