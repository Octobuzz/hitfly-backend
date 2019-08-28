<?php

namespace App\Observers;

use App\BuisnessLogic\SearchIndexing\SearchIndexer;
use App\Models\Album;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class AlbumObserver
{
    /**
     * Handle the album "created" event.
     */
    public function created(Album $album)
    {
        $indexer = new SearchIndexer();
        $indexer->index(Collection::make([$album]), 'album');
    }

    /**
     * Handle the album "updated" event.
     */
    public function updated(Album $album)
    {
        if ($album->isDirty('cover')) {
            Cache::tags(Album::class.$album->id)->flush();
        }
        $indexer = new SearchIndexer();
        $indexer->index(Collection::make([$album]), 'album');
    }

    /**
     * Handle the album "deleted" event.
     */
    public function deleted(Album $album)
    {
        $indexer = new SearchIndexer();
        $indexer->deleteFromIndex(Collection::make([$album]), 'album');
    }

    /**
     * Handle the album "restored" event.
     */
    public function restored(Album $album)
    {
        $indexer = new SearchIndexer();
        $indexer->index(Collection::make([$album]), 'album');
    }

    /**
     * Handle the album "force deleted" event.
     */
    public function forceDeleted(Album $album)
    {
        $indexer = new SearchIndexer();
        $indexer->deleteFromIndex(Collection::make([$album]), 'album');
    }
}
