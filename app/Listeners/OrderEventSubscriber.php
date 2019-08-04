<?php

namespace App\Listeners;

use App\BuisnessLogic\Notify\BaseNotifyMessage;
use App\Events\Order\CreateOrder;
use App\Models\Order;
use App\Notifications\BaseNotification;
use App\User;

class OrderEventSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(CreateOrder::class, self::class.'@buyComment');
    }

    /**
     * Куплен отзыв от звезды.
     *
     * @param Order $order
     */
    public function buyComment($createOrder)
    {
        $order = $createOrder->getOrder();
        if ('COMMENT_FROM_STAR' === $order->products->alias) {
            /** @var User | null $notifyUser */
            $notifyUser = $order->users;

            if ($order->attributes) {
                foreach ($order->attributes as $attribute) {
                    if (User::class == $attribute->model) {
                        $star = User::query()->find($attribute->pivot->value);
                    }
                }

                if (null !== $notifyUser && null !== $star) {
                    $messageData = [
                        'reviewer' => [
                            'id' => $star->id,
                            'username' => $star->username,
                            'avatar' => $star->avatar,
                        ],
                    ];

                    $baseNotifyMessage = new BaseNotifyMessage('review-request', $messageData);
                    $notifyUser->notify(new BaseNotification($baseNotifyMessage));
                }
            }
        }
    }
}
