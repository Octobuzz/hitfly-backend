<?php

namespace App\Observers;

use App\Admin\Controllers\UserController;
use App\Events\User\AttachingRolesEvent;
use App\Events\User\DetachingRolesEvent;
use App\BuisnessLogic\SearchIndexing\SearchIndexer;
use App\Helpers\FileHelpers;
use App\Http\Controllers\Auth\RegisterController;
use App\Events\User\ChangeLevelEvent;
use App\Jobs\EmailChangeJob;
use App\Models\EmailChange;
use App\Models\Purse;
use App\User;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    private $rolesToSearchIndex = ['critic', 'prof_critic', 'star', 'performer'];
    protected $indexer;

    public function __construct(SearchIndexer $indexer)
    {
        $this->indexer = $indexer;
    }

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
        $user->sendEmailVerificationNotification();
        $purse = $user->purseBonus;
        if (null === $purse) {
            $purse = new Purse();
            $purse->balance = 0;
            $purse->name = Purse::NAME_BONUS;
            $purse->user_id = $user->id;
            $user->purse()->save($purse);
            $purse->save();
        }
        if (!empty($user->username) && true === $user->inRoles($this->rolesToSearchIndex)) {
            $this->indexer->index(Collection::make([$user]), 'user');
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param User $user
     */
    public function updating(User $user)
    {
        if (null === Route::current() ||
            Route::current()->controller instanceof RegisterController ||
            Route::current()->controller instanceof UserController) {
            return;
        }

        $requestParams = Route::current()->parameters();
        if (isset($requestParams['token']) && isset($requestParams['provider'])) {//при регистрации чрез соцсети
            return;
        }

        if ($user->isDirty('email')) {
            if (null !== $user->getOriginal('email')) {
                $this->doChangeEmailProcedure($user);

                return false;
            } else {
                switch ($user->redirect) {
                    case 'TRACK_UPLOAD':  $redirectTo = '/upload'; break;
                    case 'SPEND_BONUSES':  $redirectTo = '/spend-bonuses'; break;
                    case 'HOME':  $redirectTo = '/'; break;
                    default: $redirectTo = '/';
                }
                $user->sendEmailVerificationNotification($redirectTo); //в случае если емейл изначально был пустой, нужно верифицировать
            }
        }

        if (true === $user->wasChanged('level')) {
            event(new ChangeLevelEvent($user, $user->level));
        }

        if ($user->isDirty('username') && true === $user->inRoles($this->rolesToSearchIndex)) {
            $this->indexer->index(Collection::make([$user]), 'user');
        }
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
        $this->indexer->deleteFromIndex(Collection::make([$user]), 'user');
    }

    /**
     * Handle the user "restored" event.
     *
     * @param User $user
     */
    public function restored(User $user)
    {
        if ($user->isDirty('username') && true === $user->inRoles($this->rolesToSearchIndex)) {
            $this->indexer->index(Collection::make([$user]), 'user');
        }
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param User $user
     */
    public function forceDeleted(User $user)
    {
        FileHelpers::clearingStorage($user);
        $this->indexer->deleteFromIndex(Collection::make([$user]), 'user');
    }

    public function belongsToManyAttaching($relation, $parent, $ids): void
    {
        switch ($relation) {
            case 'roles':
                event(new AttachingRolesEvent($parent, $ids));
        }
    }

    public function belongsToManyDetaching($relation, $parent, $ids): void
    {
        switch ($relation) {
            case 'roles':
                event(new DetachingRolesEvent($parent, $ids));
        }
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    private function doChangeEmailProcedure(User $user): bool
    {
        $hash = md5($user->id.$user->email.microtime());
        $emailChange = EmailChange::updateOrCreate(
            ['user_id' => $user->id],
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
}
