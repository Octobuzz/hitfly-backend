<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 19.02.19
 * Time: 12:26.
 */

namespace App\Listeners;

use App\Dictionaries\RoleDictionary;
use App\Events\User\AttachingRolesEvent;
use App\Events\User\DecreaseRoleEvent;
use App\Events\User\DetachingRolesEvent;
use App\Events\User\IncreaseRoleEvent;
use App\Models\ArtistProfile;
use App\Models\Track;
use App\Services\Auth\JsonGuard;
use App\User;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use LogicException;

class UserEventSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(Login::class, self::class.'@onUserLogin');
        $events->listen(Logout::class, self::class.'@onUserLogout');
        $events->listen('eloquent.created: '.Track::class, self::class.'@uploadFirstTrack');
        $events->listen(AttachingRolesEvent::class, self::class.'@belongsToManyAttachingRoles');
        $events->listen(DetachingRolesEvent::class, self::class.'@belongsToManyDetachingRoles');
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
        $user = User::query()->find($track->user_id);//$track->user возвращает null, так как трек еще не сохранен на данный момент(неопубликован)
        if (false === $user->isRole(RoleDictionary::ROLE_PERFORMER)) {
            $user->roles()->save(Role::query()->where('slug', '=', RoleDictionary::ROLE_PERFORMER)->first());
            //создаем профиль артиста
            $ap = new ArtistProfile([
                'user_id' => $user->id,
            ]);
            $ap->save();
        }
    }

    /**
     * Определение повышениия роли пользователя.
     *
     * @param AttachingRolesEvent $attachingRolesEvent
     */
    public function belongsToManyAttachingRoles(AttachingRolesEvent $attachingRolesEvent): void
    {
        try {
            $roles = \App\Models\Role::query()->whereIn('id', $attachingRolesEvent->getIds())->get();
            $user = $attachingRolesEvent->getUser();
            $userRoles = $user->roles;
            if ($userRoles->isEmpty()) {
                return;
            }
            $sortUserRoles = $userRoles;

            if (count($userRoles) > 1) {
                $sortUserRoles = $userRoles->sort(function ($roleA, $roleB) {
                    return RoleDictionary::getKeyRole($roleB->slug) <=> RoleDictionary::getKeyRole($roleA->slug);
                });
            }
            $sortAttachedRoles = $roles;
            if (count($roles) > 1) {
                $sortAttachedRoles = $roles->sort(function ($roleA, $roleB) {
                    return RoleDictionary::getKeyRole($roleB->slug) <=> RoleDictionary::getKeyRole($roleA->slug);
                });
            }

            $maxRole = $sortUserRoles->pop();
            $maxRoleAttached = $sortAttachedRoles->pop();

            if (RoleDictionary::getKeyRole($maxRole->slug) < RoleDictionary::getKeyRole($maxRoleAttached->slug)) {
                event(new IncreaseRoleEvent($user, $maxRoleAttached));
            }
        } catch (LogicException $exception) {
            Log::alert($exception->getMessage(), $exception->getTrace());
        }
    }

    public function belongsToManyDetachingRoles(DetachingRolesEvent $detachingRolesEvent): void
    {
        try {
            $user = $detachingRolesEvent->getUser();
            $roles = \App\Models\Role::query()->whereIn('id', $detachingRolesEvent->getIds())->get();
            $minRolesDetach = $roles;
            if (count($roles) > 1) {
                $sortUserRoles = $roles->sort(function ($roleA, $roleB) {
                    return  RoleDictionary::getKeyRole($roleA->slug) <=> RoleDictionary::getKeyRole($roleB->slug);
                });
            }
            $minRoleDetach = $minRolesDetach->pop();
            if(null !== $minRoleDetach){
                event(new DecreaseRoleEvent($user, $minRoleDetach));
            }
        } catch (LogicException $exception) {
            Log::alert($exception->getMessage(), $exception->getTrace());
        }
    }
}
