<?php

namespace App\Observers;

use App\Dictionaries\OrderStatusDictionary;
use App\Events\Order\CreateOrder;
use App\Events\Order\DoneOrder;
use App\Models\Album;
use App\Models\Order;
use ReflectionException;

class OrderObserver
{
    /**
     * Handle the album "updated" event.
     *
     * @param Order $order
     *
     * @throws ReflectionException
     */
    public function updated(Order $order)
    {
        if (OrderStatusDictionary::STATUS_DONE === $order->status) {
            event(new DoneOrder($order));
        }
    }

    public function created(Order $order)
    {
        if (OrderStatusDictionary::STATUS_NEW === $order->status) {
            event(new CreateOrder($order));
        }
    }
}
