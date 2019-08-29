<?php

namespace App\Observers;

use App\BuisnessLogic\SearchIndexing\SearchIndexer;
use App\Jobs\Track\CreatePlayTimeJob;
use App\Models\Track;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
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
        if (empty($track->state)) {
            $track->state = Track::CREATE_WAVE;
        }

        return true;
    }

    /**
     * @param Track $track
     *
     * @return bool
     */
    public function created(Track $track)
    {
        dispatch(new CreatePlayTimeJob($track));

        return true;
    }

    /**
     * Handle the collection "updated" event.
     *
     * @param \App\Models\Track $track
     */
    public function updated(Track $track)
    {
        if ($track->isDirty('cover')) {
            Cache::tags(Track::class.$track->id)->flush();
        }
        if (Track::PUBLISHED === $track->state) {
            $indexer = new SearchIndexer();
            $indexer->index(Collection::make([$track]), 'track');
        }
    }

    /**
     * Handle the collection "deleted" event.
     *
     * @param \App\Models\Track $track
     */
    public function deleted(Track $track)
    {
        Storage::delete('public/music/'.$track->user_id.DIRECTORY_SEPARATOR.$track->filename);
        $indexer = new SearchIndexer();
        $indexer->deleteFromIndex(Collection::make([$track]), 'track');
    }

    /**
     * Handle the collection "restored" event.
     *
     * @param \App\Models\Track $collection
     */
    public function restored(Track $track)
    {
        $indexer = new SearchIndexer();
        if (Track::PUBLISHED === $track->state) {
            $indexer->index(Collection::make([$track]), 'track');
        }
    }

    /**
     * Handle the collection "force deleted" event.
     *
     * @param \App\Models\Track $collection
     */
    public function forceDeleted(Track $track)
    {
        $indexer = new SearchIndexer();
        $indexer->deleteFromIndex(Collection::make([$track]), 'track');
    }
}
