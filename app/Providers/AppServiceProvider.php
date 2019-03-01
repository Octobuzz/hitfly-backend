<?php

namespace App\Providers;

use App\Models\MusicGroup;
use App\Observers\MusicGroupObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        MusicGroup::observe(MusicGroupObserver::class);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}
