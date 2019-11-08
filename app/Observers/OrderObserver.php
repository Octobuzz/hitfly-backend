<?php

namespace App\Observers;

use App\BuisnessLogic\SearchIndexing\SearchIndexer;
use App\Events\Order\DoneOrder;
use App\Models\Album;
use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
        if ($order->status === Order::STATUS_DONE) {
            event(new DoneOrder($order));
        }

    }

}
