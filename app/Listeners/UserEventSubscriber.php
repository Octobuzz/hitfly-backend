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
            $roles = Role::query()->whereIn('id', $attachingRolesEvent->getIds())->get();
            $user = $attachingRolesEvent->getUser();
            $userRoles = $user->roles;
            $sortUserRoles = $userRoles;
            if (count($userRoles) > 1) {
                $sortUserRoles = usort($userRoles, static function ($roleA, $roleB) {
                    return RoleDictionary::getKeyRole($roleA->slug) <=> RoleDictionary::getKeyRole($roleB->slug);
                });
            }
            $sortAttachedRoles = $roles;
            if (count($roles) > 1) {
                $sortAttachedRoles = usort($roles, static function ($roleA, $roleB) {
                    return RoleDictionary::getKeyRole($roleA->slug) <=> RoleDictionary::getKeyRole($roleB->slug);
                });
            }
            $maxRole = array_pop($sortUserRoles);
            $maxRoleAttached = array_pop($sortAttachedRoles);
            if (RoleDictionary::getKeyRole($maxRole->slug) < RoleDictionary::getKeyRole($maxRoleAttached->slug)) {
                event(new IncreaseRoleEvent($user, $maxRoleAttached));
            }
        } catch (LogicException $exception) {
            Log::alert($exception->getMessage(), $exception->getTrace());
        }
    }
}
