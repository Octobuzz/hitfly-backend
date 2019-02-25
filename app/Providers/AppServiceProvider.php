<?php

namespace App\Providers;

use App\Models\Collection;
use App\Observers\CollectionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Collection::observe(CollectionObserver::class);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}
