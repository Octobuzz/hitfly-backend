<?php

namespace App\Observers;

use App\Models\Track;
use Illuminate\Support\Facades\App;
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
        $pathTrack = Storage::disk('public')->path($track->filename);
        $track->track_hash = md5_file($pathTrack);

        if (empty($track->state)) {
            $track->state = 'fileload';
        }

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
