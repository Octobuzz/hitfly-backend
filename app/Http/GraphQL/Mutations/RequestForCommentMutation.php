<?php

namespace App\Http\GraphQL\Mutations;

use App\Events\Order\CreateOrder;
use App\Models\BonusType;
use App\Models\Operation;
use App\Models\Order;
use App\Models\Product;
use App\Models\Track;
use App\Rules\IsStar;
use App\Rules\OwnerTrack;
use App\User;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;

class RequestForCommentMutation extends Mutation
{
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
                'type' => Type::int(),
                'description' => 'ID критика или звезды',
                'rules' => ['required', new IsStar()],
            ],
            'trackId' => [
                'type' => Type::int(),
                'description' => 'ID комментируемого трека',
                'rules' => ['required', new OwnerTrack()],
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $product = Product::query()->where('alias', 'COMMENT_FROM_STAR')->first();
        if (null === $product) {
            return  new \Exception('Нет такого товара');
        } else {
            $bonusType = BonusType::query()->where('constant_name', '=', 'BUY_PRODUCT')->first();
            $operation = new Operation([
                'direction' => Operation::DIRECTION_DECREASE,
                'amount' => $product->price,
                'description' => $bonusType->name." {$product->name}",
                'type_id' => $bonusType->id,
            ]);
            /** @var User $user */
            $user = Auth::user();
            $operationResult = $user->purseBonus->processOperation($operation);
            if (false === $operationResult) {
                throw new \Exception('На вашем счете недостаточно бонусов');
            }
            $order = new Order([
                'name' => $product->name,
                'user_id' => Auth::user()->id,
                'product_id' => $product->id,
            ]);
            $order->save();
            foreach ($product->attributes as $attr) {
                if (Track::class === $attr->model) {
                    $productAttr = $args['trackId'];
                } else {
                    $productAttr = $args['userId'];
                }

                $order->attributes()->attach($attr->id, ['value' => $productAttr]);
            }
            event(new CreateOrder($order));
        }

        return $operation;
    }
}
