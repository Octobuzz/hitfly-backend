<?php

namespace App\Observers;

use App\Models\Track;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class TrackObserver
{
    /**
     * @param Track $collection
     *
     * @return bool
     */
    public function creating(Track $collection)
    {
        $user = Auth::user();
        if (App::environment('local')) {
            if (null === $user) {
                $user = \App\User::inRandomOrder()->first();
            }
        }
        $collection->user_id = $user->id;

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
     * @param \App\Models\Track $collection
     */
    public function deleted(Track $collection)
    {
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
