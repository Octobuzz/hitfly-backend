<?php

namespace App\Http\GraphQL\Query;

use App\Dictionaries\OrderStatusDictionary;
use App\Models\Order;
use App\Models\Product;
use App\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

/**
 * Class AlbumQuery.
 */
class RequestsForCommentsQuery extends Query
{
    protected $attributes = [
        'name' => 'RequestsForComments',
        'description' => 'Запросы на отзывы',
    ];

    public function type()
    {
        return \GraphQL::paginate('OrderType');
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
        $user = Auth::guard('json')->user();
        if (null === $user) {
            return null;
        }
        $product = Product::query()->where('products.alias', 'COMMENT_FROM_STAR')->first();

        //получение атрибутов продукта(id атрибута продукта нужен будет для получения заказов отзывов заказаных у определенной звезды)
        foreach ($product->attributes as $attr) {
            if (User::class === $attr->model) {
                $attrId = $attr->id;
            }
        }
        if (isset($attrId)) {
            $orderList = $this->getOrderList($attrId, $product->id);
        } else {
            throw new  \Exception('Не найден ID атрибута');
        }

        $response = $orderList->paginate($args['limit'], ['*'], 'page', $args['page']);

        return $response;
    }

    public function getOrderList($attrId, $productId)
    {
        $query = Order::query()->select('orders.*', 'value.value');
        $query->where('orders.status', OrderStatusDictionary::STATUS_NEW);
        $query->where('orders.product_id', $productId);

        $query->leftJoin('order_attribute_value as value', function ($join) {
            $join->on('orders.id', '=', 'value.order_id');
        });
        $query->where('value.attribute_id', $attrId);
        $query->where('value.value', Auth::guard('json')->user()->id);
        $query->orderBy('orders.created_at');

        return $query;
    }
}
