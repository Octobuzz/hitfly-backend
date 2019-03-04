<?php

namespace App\Observers;

use App\Models\MusicGroup;
use App\User;

class MusicGroupObserver
{
    /**
     * Handle the music group "created" event.
     *
     * @param MusicGroup $musicGroup
     */
    public function created(MusicGroup $musicGroup)
    {
        /** @var User $user */
        $user = \Auth::guard('json')->user();
        $musicGroup->setUser($user);
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
