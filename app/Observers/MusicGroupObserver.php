<?php

namespace App\Observers;

use App\Models\MusicGroup;
use App\User;
use Illuminate\Support\Facades\App;

class MusicGroupObserver
{
    /**
     * @param MusicGroup $musicGroup
     */
    public function creating(MusicGroup $musicGroup)
    {
        $user = \Auth::guard('json')->user();

        if (App::environment('local')) {
            if (null === $user) {
                $user = User::inRandomOrder()->first();
            }
        }
        $musicGroup->setUser($user);
    }

    /**
     * Handle the music group "created" event.
     *
     * @param MusicGroup $musicGroup
     */
    public function created(MusicGroup $musicGroup)
    {
    }

    /**
     * Handle the music group "updated" event.
     *
     * @param MusicGroup $musicGroup
     */
    public function updated(MusicGroup $musicGroup)
    {
    }

    /**
     * Handle the music group "deleted" event.
     *
     * @param MusicGroup $musicGroup
     */
    public function deleted(MusicGroup $musicGroup)
    {
    }

    /**
     * Handle the music group "restored" event.
     *
     * @param MusicGroup $musicGroup
     */
    public function restored(MusicGroup $musicGroup)
    {
    }

    /**
     * Handle the music group "force deleted" event.
     *
     * @param MusicGroup $musicGroup
     */
    public function forceDeleted(MusicGroup $musicGroup)
    {
    }
}
