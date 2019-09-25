<?php

namespace App\Observers;

use App\BuisnessLogic\SearchIndexing\SearchIndexer;
use App\Models\Album;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class AlbumObserver
{
    protected $indexer;

    public function __construct(SearchIndexer $indexer)
    {
        $this->indexer = $indexer;
    }

    /**
     * Handle the album "created" event.
     */
    public function created(Album $album)
    {
        $this->indexer->index(Collection::make([$album]), 'album');
    }

    /**
     * Handle the album "updated" event.
     *
     * @param Album         $album
     * @param SearchIndexer $indexer
     */
    public function updated(Album $album)
    {
        if ($album->isDirty('cover')) {
            Cache::tags(Album::class.$album->id)->flush();
        }
        $this->indexer->index(Collection::make([$album]), 'album');
    }

    /**
     * Handle the album "deleted" event.
     */
    public function deleted(Album $album)
    {
        $this->indexer->deleteFromIndex(Collection::make([$album]), 'album');
    }

    /**
     * Handle the album "restored" event.
     */
    public function restored(Album $album)
    {
        $this->indexer->index(Collection::make([$album]), 'album');
    }

    /**
     * Handle the album "force deleted" event.
     */
    public function forceDeleted(Album $album)
    {
        $this->indexer->deleteFromIndex(Collection::make([$album]), 'album');
    }
}
