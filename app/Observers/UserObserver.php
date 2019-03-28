<?php

namespace App\Observers;

use App\User;
use Encore\Admin\Auth\Database\Role;

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
        if ($user->isRole('listener')) {
            $user->roles()->attach($role->id);
            $user->save();
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
