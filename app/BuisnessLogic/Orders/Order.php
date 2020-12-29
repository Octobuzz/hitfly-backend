<?php

namespace App\BuisnessLogic\Orders;

use App\Events\Order\CreateOrder;
use App\Models\BonusType;
use App\Models\Operation;
use App\Models\Product;
use App\Models\Track;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Rebing\GraphQL\Error\ValidationError;

class Order
{
    /**
     * метод покупки товара.
     *
     * @param Product $product
     * @param null    $attributes
     *
     * @return Operation
     *
     * @throws \Exception
     */
    public function buyProduct(Product $product, $attributes = null)
    {
        $bonusType = BonusType::query()->where('constant_name', '=', 'BUY_PRODUCT')->first();
        $operation = new Operation([
            'direction' => Operation::DIRECTION_DECREASE,
            'amount' => $product->price,
            'description' => $bonusType->name." {$product->name}",
            'type_id' => $bonusType->id,
        ]);
        /** @var User $user */
        $user = Auth::user();

        $errorMessage = null;
        \DB::beginTransaction();
        try {
            $operationResult = $user->purseBonus->processOperation($operation);
            if (false === $operationResult) {
                throw new ValidationError('На вашем счету недостаточно бонусов');
            }
            $order = new \App\Models\Order([
                'name' => $product->name,
                'user_id' => Auth::user()->id,
                'description' => $product->description,
                'product_id' => $product->id,
            ]);
            $order->save();
            \DB::commit();
        } catch (ValidationError $error) {
            $errorMessage = $error->getMessage();
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            $errorMessage = 'Ошибка проведения операции';
        } finally {
            if (null === $errorMessage) {
                event(new CreateOrder($order));
            } else {
                \DB::rollback();
                throw new ValidationError($errorMessage);
            }
        }

        return $operation;
    }

    private function commentStar($product, $attributes, $order)
    {
        foreach ($product->attributes as $attr) {
            if (Track::class === $attr->model) {
                $productAttr = $attributes['trackId'];
            } else {
                $productAttr = $attributes['userId'];
            }

            $order->attributes()->attach($attr->id, ['value' => $productAttr]);
        }
    }
}
