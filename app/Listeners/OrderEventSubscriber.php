<?php

namespace App\Listeners;

use App\BuisnessLogic\Notify\BaseNotifyMessage;
use App\Dictionaries\RoleDictionary;
use App\Events\Order\CreateOrder;
use App\Events\Order\DoneOrder;
use App\Jobs\NewOrderAdminJob;
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
        $events->listen(CreateOrder::class, self::class.'@createOrder');
        $events->listen(DoneOrder::class, self::class.'@doneOrder');
    }

    public function createOrder($createOrder)
    {
        $order = $createOrder->getOrder();
        $adminList = User::filterRoles([RoleDictionary::ROLE_ADMIN])->get();
        foreach ($adminList as $admin) {
            dispatch(new NewOrderAdminJob($admin, $order))->onQueue('low');
        }
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

    /**
     * заказ выполнен.
     *
     * @param Order $order
     */
    public function doneOrder($doneOrder)
    {
        //todo: отиравлять уведомление владельцу о выполнении заказа
    }
}
