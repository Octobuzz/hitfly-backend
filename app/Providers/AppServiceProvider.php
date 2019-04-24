<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\MusicGroup;
use App\Models\Track;
use App\Observers\CommentObserver;
use App\Observers\MusicGroupObserver;
use App\Models\Collection;
use App\Observers\CollectionObserver;
use App\Observers\UserObserver;
use App\User;
use App\Observers\TrackObserver;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Date\Date;
use Encore\Admin\Config\Config;
use Illuminate\Support\Facades\Schema;

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
        Track::observe(TrackObserver::class);
        Comment::observe(CommentObserver::class);
        Date::setlocale(config('app.locale'));
        Validator::extend('favourites_unique_validate', 'App\Validation\FavouritesUniqueValidator@validate');

        $table = config('admin.extensions.config.table', 'admin_config');
        if (Schema::hasTable($table)) {
            Config::load();
        }
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}
