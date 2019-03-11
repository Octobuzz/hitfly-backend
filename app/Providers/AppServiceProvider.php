<?php

namespace App\Providers;

use App\Models\Collection;
use App\Models\MusicGroup;
use App\Models\Track;
use App\Observers\MusicGroupObserver;
use App\Observers\CollectionObserver;
use App\Observers\TrackObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        MusicGroup::observe(MusicGroupObserver::class);
        Collection::observe(CollectionObserver::class);
        Track::observe(TrackObserver::class);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}
