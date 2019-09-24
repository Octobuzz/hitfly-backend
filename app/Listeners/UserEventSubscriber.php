<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 19.02.19
 * Time: 12:26.
 */

namespace App\Listeners;

use App\Models\ArtistProfile;
use App\Models\Track;
use App\Services\Auth\JsonGuard;
use App\User;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Cookie;

class UserEventSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen('Illuminate\Auth\Events\Login', 'App\Listeners\UserEventSubscriber@onUserLogin');
        $events->listen('Illuminate\Auth\Events\Logout', 'App\Listeners\UserEventSubscriber@onUserLogout');
        $events->listen('eloquent.created: '.Track::class, self::class.'@uploadFirstTrack');
    }

    /**
     * Handle user login events.
     *
     * @param Login $event
     */
    public function onUserLogin($event)
    {
        /** @var User $user */
        $user = $event->user;

        if (Cookie::has(JsonGuard::HEADER_NAME_TOKEN)) {
            Cookie::queue(Cookie::forget(JsonGuard::HEADER_NAME_TOKEN));
        }

        $user->generateAccessToken();
        $user->save();

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
     * при загрузке трека слушателем не меняется роль в админке на испольнительы.
     *
     * @param Track $track
     */
    public function uploadFirstTrack(Track $track)
    {
        /** @var User $user */
        $user = $track->user;

        if (false === $user->isRole(User::ROLE_PERFORMER)) {
            $user->roles()->save(Role::query()->where('slug', '=', User::ROLE_PERFORMER)->first());
            //создаем профиль артиста
            $ap = new ArtistProfile([
                'user_id' => $user->id,
            ]);
            $ap->save();
        }
    }
}
