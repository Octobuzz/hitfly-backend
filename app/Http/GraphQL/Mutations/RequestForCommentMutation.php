<?php

namespace App\Http\GraphQL\Mutations;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Product;
use App\Rules\IsStar;
use App\Rules\OwnerTrack;
use Rebing\GraphQL\Error\ValidationError;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;

class RequestForCommentMutation extends Mutation
{
    use GraphQLAuthTrait;
    private $order;

    public function __construct($attributes = [], \App\BuisnessLogic\Orders\Order $order)
    {
        parent::__construct($attributes);
        $this->order = $order;
    }

    protected $attributes = [
        'name' => 'RequestForComment',
        'description' => 'Запрос комментария у звезды',
    ];

    public function type()
    {
        return \GraphQL::type('OperationType');
    }

    public function args()
    {
        return [
            'userId' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID критика или звезды',
                'rules' => ['required', new IsStar()],
            ],
            'trackId' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID комментируемого трека',
                'rules' => ['required', new OwnerTrack()],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $product = Product::query()->where('alias', 'COMMENT_FROM_STAR')->first();
        if (null === $product) {
            return  new ValidationError('Нет такого товара');
        }

        return $this->order->buyProduct($product, $args);
    }
}
