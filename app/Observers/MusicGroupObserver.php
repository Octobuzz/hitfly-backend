<?php

namespace App\Observers;

use App\Models\MusicGroup;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class MusicGroupObserver
{
    /**
     * @param MusicGroup $musicGroup
     */
    public function creating(MusicGroup $musicGroup)
    {
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
        if ($musicGroup->isDirty('avatar_group')) {
            Cache::tags(MusicGroup::class.$musicGroup->id)->flush();
        }
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
