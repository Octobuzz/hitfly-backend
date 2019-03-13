<?php

namespace App\Providers;

use App\Models\MusicGroup;
use App\Observers\MusicGroupObserver;
use App\Models\Collection;
use App\Observers\CollectionObserver;
use App\Observers\UserObserver;
use App\User;
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
        User::observe(UserObserver::class);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}
