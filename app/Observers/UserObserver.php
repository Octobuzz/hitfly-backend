<?php

namespace App\Observers;

use App\Jobs\UserRegisterJob;
use App\User;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param \App\User $user
     */
    public function created(User $user)
    {
        $role = Role::query()->where('slug', '=', 'listener')->first();
        //добавление роли "слушатель"
        $user->roles()->attach($role->id);
        $user->save();
        //отправка письма о завершении регистрации
        if ($user->email && App::environment('local') && null !== Auth::user()) {
            dispatch(new UserRegisterJob($user))->onQueue('low');
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param \App\User $user
     */
    public function updated(User $user)
    {

    }
    public function saving(User $user)
    {
        //удаление ресайзов аватарки
       if($user->isDirty('avatar')){
           $path = Storage::disk('public')->getAdapter()->getPathPrefix();
           $avatarFileName  = pathinfo($user->getOriginal('avatar'), PATHINFO_FILENAME);
           $imagePath = "avatars/$user->id/{$avatarFileName}_*";
           array_map("unlink", glob($path.$imagePath));
       }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param \App\User $user
     */
    public function deleted(User $user)
    {
    }

    /**
     * Handle the user "restored" event.
     *
     * @param \App\User $user
     */
    public function restored(User $user)
    {
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param \App\User $user
     */
    public function forceDeleted(User $user)
    {
    }
}
