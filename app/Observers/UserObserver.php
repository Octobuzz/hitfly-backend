<?php

namespace App\Observers;

use App\Admin\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Events\User\ChangeLevelEvent;
use App\Jobs\EmailChangeJob;
use App\Jobs\UserRegisterJob;
use App\Models\EmailChange;
use App\Models\Purse;
use App\User;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param User $user
     */
    public function created(User $user)
    {
        $role = Role::query()->where('slug', '=', 'listener')->first();
        //добавление роли "слушатель"
        $user->roles()->attach($role->id);
        $user->save();
        $purse = $user->purseBonus;
        if (null === $purse) {
            $purse = new Purse();
            $purse->balance = 0;
            $purse->name = Purse::NAME_BONUS;
            $purse->user_id = $user->id;
            $user->purse()->save($purse);
            $purse->save();
        }
//        //отправка письма о завершении регистрации
//        if ($user->email && App::environment('local') /*&& null !== Auth::user()*/) {
//            dispatch(new UserRegisterJob($user))->onQueue('low');
//        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param User $user
     *
     * @return bool
     */
    public function updating(User $user)
    {
        if (null === Route::current() ||
            Route::current()->controller instanceof RegisterController ||
            Route::current()->controller instanceof UserController) {
            return true;
        }
        $requestParams = Route::current()->parameters();
        if ($user->isDirty('email') && null !== $user->getOriginal('email') && !isset($requestParams['token']) && !isset($requestParams['provider'])) {
            $hash = md5($user->id.$user->email.microtime());
            $emailChange = EmailChange::updateOrCreate(
                ['new_email' => $user->email],
                [
                   'user_id' => $user->id,
                   'new_email' => $user->email,
                   'token' => $hash,
                ]
            );
            $url = config('app.url').'/email-change/'.$user->id.'/'.$hash;

            dispatch(new EmailChangeJob($user, $user->email, $url))->onQueue('low');
            return false;
        }

        if (true === $user->wasChanged('level')) {
            event(new ChangeLevelEvent($user, $user->level));
        }

        return true;
    }

    public function saving(User $user)
    {
        //удаление ресайзов аватарки
        if ($user->isDirty('avatar')) {
            $path = Storage::disk('public')->getAdapter()->getPathPrefix();
            $avatarFileName = pathinfo($user->getOriginal('avatar'), PATHINFO_FILENAME);
            $imagePath = "avatars/$user->id/{$avatarFileName}_*";
            array_map('unlink', glob($path.$imagePath));
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param User $user
     */
    public function deleted(User $user)
    {
    }

    /**
     * Handle the user "restored" event.
     *
     * @param User $user
     */
    public function restored(User $user)
    {
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param User $user
     */
    public function forceDeleted(User $user)
    {
    }
}
