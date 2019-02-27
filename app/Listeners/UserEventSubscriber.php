<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 19.02.19
 * Time: 12:26.
 */

namespace App\Listeners;

use App\Services\Auth\JsonGuard;
use App\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Cookie;

class UserEventSubscriber
{
    /**
     * Handle user login events.
     *
     * @param Login $event
     */
    public function onUserLogin($event)
    {
        /** @var User $user */
        $user = $event->user;
        if (!Cookie::has(JsonGuard::HEADER_NAME_TOKEN)) {
            if (empty($user->access_token)) {
                $user->generateAccessToken();
                $user->save();
            }
        } else {
            Cookie::queue(Cookie::forget(JsonGuard::HEADER_NAME_TOKEN));
        }

        //todo update avatar if null

        Cookie::queue(JsonGuard::HEADER_NAME_TOKEN, $user->access_token);
    }

    /**
     * Handle user logout events.
     *
     * @param Logout $event
     */
    public function onUserLogout($event)
    {
        Cookie::queue(Cookie::forget(JsonGuard::HEADER_NAME_TOKEN));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@onUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\UserEventSubscriber@onUserLogout'
        );
    }
}
