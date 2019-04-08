<?php

namespace App\Observers;

use App\Models\Track;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TrackObserver
{
    /**
     * @param Track $track
     *
     * @return bool
     */
    public function creating(Track $track)
    {
        $user = Auth::user();
        if (App::environment('local')) {
            if (null === $user) {
                $user = \App\User::inRandomOrder()->first();
            }
        }
        $track->user_id = $user->id;

        return true;
    }

    /**
     * Handle the collection "updated" event.
     *
     * @param \App\Models\Track $collection
     */
    public function updated(Track $collection)
    {
    }

    /**
     * Handle the collection "deleted" event.
     *
     * @param \App\Models\Track $track
     */
    public function deleted(Track $track)
    {
        Storage::delete('public/music/'.$track->user_id.DIRECTORY_SEPARATOR.$track->filename);
    }

    /**
     * Handle the collection "restored" event.
     *
     * @param \App\Models\Track $collection
     */
    public function restored(Track $collection)
    {
    }

    /**
     * Handle the collection "force deleted" event.
     *
     * @param \App\Models\Track $collection
     */
    public function forceDeleted(Track $collection)
    {
    }
}
