<?php

namespace App\Observers;

use App\Models\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class CollectionObserver
{
    /**
     * Handle the collection "creating" event.
     *
     * @param \App\Models\Track $collection
     */
    public function creating(Collection $collection)
    {
        $user = Auth::user();
        if (App::environment('local')) {
            $user = \App\User::inRandomOrder()->first();
        }
        $collection->user_id = $user->id;
        $collection->is_admin = in_array($user->id, $user->has('roles')->pluck('id')->toArray());

        return true;
    }

    /**
     * Handle the collection "updated" event.
     *
     * @param \App\Models\Track $collection
     */
    public function updated(Collection $collection)
    {
    }

    /**
     * Handle the collection "deleted" event.
     *
     * @param \App\Models\Track $collection
     */
    public function deleted(Collection $collection)
    {
    }

    /**
     * Handle the collection "restored" event.
     *
     * @param \App\Models\Track $collection
     */
    public function restored(Collection $collection)
    {
    }

    /**
     * Handle the collection "force deleted" event.
     *
     * @param \App\Models\Track $collection
     */
    public function forceDeleted(Collection $collection)
    {
    }
}
