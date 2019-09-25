<?php

namespace App\Observers;

use App\BuisnessLogic\SearchIndexing\SearchIndexer;
use App\Models\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class CollectionObserver
{
    protected $indexer;

    public function __construct(SearchIndexer $indexer)
    {
        $this->indexer = $indexer;
    }

    /**
     * Handle the collection "creating" event.
     *
     * @param \App\Models\Track $collection
     */
    public function creating(Collection $collection)
    {
        if (!empty($collection->title)) {
            $this->indexer->index(\Illuminate\Support\Collection::make([$collection]), 'collection');
        }
    }

    /**
     * Handle the collection "updated" event.
     *
     * @param \App\Models\Track $collection
     */
    public function updated(Collection $collection)
    {
        if ($collection->isDirty('cover')) {
            Cache::tags(Collection::class.$collection->id)->flush();
        }
        if ($collection->isDirty('title')) {
            $this->indexer->index(\Illuminate\Support\Collection::make([$collection]), 'collection');
        }
    }

    /**
     * Handle the collection "deleted" event.
     *
     * @param \App\Models\Track $collection
     */
    public function deleted(Collection $collection)
    {
        $this->indexer->deleteFromIndex(\Illuminate\Support\Collection::make([$collection]), 'collection');
    }

    /**
     * Handle the collection "restored" event.
     *
     * @param \App\Models\Track $collection
     */
    public function restored(Collection $collection)
    {
        if ($collection->isDirty('title')) {
            $this->indexer->index(\Illuminate\Support\Collection::make([$collection]), 'collection');
        }
    }

    /**
     * Handle the collection "force deleted" event.
     *
     * @param \App\Models\Track $collection
     */
    public function forceDeleted(Collection $collection)
    {
        $this->indexer->deleteFromIndex(\Illuminate\Support\Collection::make([$collection]), 'collection');
    }
}
