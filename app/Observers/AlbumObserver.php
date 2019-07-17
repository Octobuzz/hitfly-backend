<?php

namespace App\Observers;

use App\Models\Album;
use Illuminate\Support\Facades\Cache;

class AlbumObserver
{
    /**
     * Handle the album "created" event.
     */
    public function created(Album $album)
    {
    }

    /**
     * Handle the album "updated" event.
     */
    public function updated(Album $album)
    {
        if ($album->isDirty('cover')) {
            Cache::tags(Album::class.$album->id)->flush();
        }
    }

    /**
     * Handle the album "deleted" event.
     */
    public function deleted(Album $album)
    {
    }

    /**
     * Handle the album "restored" event.
     */
    public function restored(Album $album)
    {
    }

    /**
     * Handle the album "force deleted" event.
     */
    public function forceDeleted(Album $album)
    {
    }
}
