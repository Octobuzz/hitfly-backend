<?php

namespace App\Providers;

use App\BuisnessLogic\SearchIndexing\SearchIndexer;
use App\BuisnessLogic\Services\SearchService\ElasticsearchService;
use App\BuisnessLogic\Top\TopWeekly;
use App\Interfaces\Top\TopWeeklyInterface;
use App\Models\Album;
use App\Models\Comment;
use App\Models\MusicGroup;
use App\Models\Track;
use App\Observers\AlbumObserver;
use App\Observers\CommentObserver;
use App\Observers\MusicGroupObserver;
use App\Models\Collection;
use App\Observers\CollectionObserver;
use App\Observers\UserObserver;
use App\Repositories\AlbumRepository;
use App\Repositories\TrackRepository;
use App\Repositories\UserRepository;
use App\User;
use App\Observers\TrackObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Date\Date;
use Encore\Admin\Config\Config;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public $singletons = [
        TopWeeklyInterface::class => TopWeekly::class,
        AlbumRepository::class => AlbumRepository::class,
        UserRepository::class => UserRepository::class,
        TrackRepository::class => TrackRepository::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (config('app.scheme') === true) {
            URL::forceScheme('https');
        }
        MusicGroup::observe(MusicGroupObserver::class);
        Collection::observe(CollectionObserver::class);
        User::observe(UserObserver::class);
        Track::observe(TrackObserver::class);
        Comment::observe(CommentObserver::class);
        Album::observe(AlbumObserver::class);
        Date::setlocale(config('app.locale'));
        Validator::extend('favourites_unique_validate', 'App\Validation\FavouritesValidator@validate');
        Validator::extend('follow_unique_validate', 'App\Validation\FollowValidator@validate');
        Validator::extend('follow_myself_validate', 'App\Validation\FollowValidator@followMyself');
        Validator::extend('favourites_delete_validate', 'App\Validation\FavouritesValidator@validateDelete');
        Validator::extend('follow_delete_validate', 'App\Validation\FollowValidator@validateDelete');
        Validator::extend('mutually_exclusive_args', 'App\Validation\ManuallyExclusiveArgs@validate');
        Validator::extend('album_delete_validate', 'App\Validation\AlbumValidator@validateDelete');
        Validator::extend('music_group_delete_validate', 'App\Validation\MusicGroupValidator@validateDelete');
        Validator::extend('collection_delete_validate', 'App\Validation\CollectionValidator@validateDelete');
        Validator::extend('remove_track_from_album_validate', 'App\Validation\AlbumValidator@removeTrackFromAlbum');
        Validator::extend('remove_track_from_collection_validate', 'App\Validation\CollectionValidator@removeTrackFromCollection');
        Validator::extend('login_password_correct', 'App\Validation\LoginPasswordCorrect@validate');
        $this->app->singleton(SearchIndexer::class, function ($app) {
            return new SearchIndexer();
        });

        $this->app->singleton('SearchService', function ($app) {
            return new ElasticsearchService();
        });

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
