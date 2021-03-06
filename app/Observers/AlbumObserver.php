<?php

namespace App\Observers;

use App\BuisnessLogic\SearchIndexing\SearchIndexer;
use App\Helpers\FileHelpers;
use App\Models\Album;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use ReflectionException;

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
        try {
            $this->indexer->index(Collection::make([$album]), 'album');
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage(), $exception->getTrace());
        }
    }

    /**
     * Handle the album "updated" event.
     *
     * @param Album $album
     *
     * @throws ReflectionException
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
     *
     * @param Album $album
     */
    public function deleted(Album $album): void
    {
        try {
            $this->indexer->deleteFromIndex(Collection::make([$album]), 'album');
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage(), $exception->getTrace());
        }
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
        FileHelpers::clearingStorage($album);
        $this->indexer->deleteFromIndex(Collection::make([$album]), 'album');
    }
}
