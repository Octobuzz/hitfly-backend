<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index')->name(\App\Admin\Controllers\HomeController::ROUTE_NAME);
    $router->resource('/auth/users', UserController::class)->names(\App\Admin\Controllers\UserController::ROUTE_NAME);
    $router->resource('/auth/roles', RoleController::class)->names(\App\Admin\Controllers\RoleController::ROUTE_NAME);
    $router->resource('/auth/permissions', PermissionController::class)->names(\App\Admin\Controllers\PermissionController::ROUTE_NAME);
    $router->resource('/auth/menu', MenuController::class)->names(\App\Admin\Controllers\MenuController::ROUTE_NAME);
    $router->resource('/auth/logs', LogController::class)->names(\App\Admin\Controllers\LogController::ROUTE_NAME);
    $router->resource('/genre', GenreController::class)->names(\App\Admin\Controllers\GenreController::ROUTE_NAME);
    $router->resource('/comment', CommentController::class)->names(\App\Admin\Controllers\CommentController::ROUTE_NAME);
    $router->resource('/collection', CollectionController::class)->names(\App\Admin\Controllers\CollectionController::ROUTE_NAME);
    $router->resource('/album', AlbumController::class)->names(\App\Admin\Controllers\AlbumController::ROUTE_NAME);
    $router->resource('/music/group', MusicGroupController::class)->names(\App\Admin\Controllers\MusicGroupController::ROUTE_NAME);
    $router->resource('/track', TrackController::class)->names(\App\Admin\Controllers\TrackController::ROUTE_NAME);
    $router->resource('/chart', ChartController::class)->names(\App\Admin\Controllers\ChartController::ROUTE_NAME);
    $router->resource('/favourite', FavouriteController::class)->names(\App\Admin\Controllers\FavouriteController::ROUTE_NAME);
    $router->resource('/city', CityController::class)->names(\App\Admin\Controllers\CityController::ROUTE_NAME);
    $router->resource('/bonus/types', BonusTypeController::class)->names(\App\Admin\Controllers\BonusTypeController::ROUTE_NAME);
    $router->resource('/auth/artist', ArtistController::class)->names(\App\Admin\Controllers\ArtistController::ROUTE_NAME);
    $router->resource('/news', NewsController::class)->names(\App\Admin\Controllers\NewsController::ROUTE_NAME);
    $router->resource('/bonus/operations', BonusOperationController::class)->names(\App\Admin\Controllers\BonusOperationController::ROUTE_NAME);
    $router->resource('/lifehacks', LifehackController::class)->names(\App\Admin\Controllers\LifehackController::ROUTE_NAME);
    $router->resource('/tags', TagController::class)->names(\App\Admin\Controllers\TagController::ROUTE_NAME);

    $router->post('/auth/users/add-user', UserController::class.'@addUser')->name('add_user');

    $router->get('/api/users', '\App\Admin\Controllers\UserController@users');
    $router->get('/api/genres', '\App\Admin\Controllers\GenreController@getGenres');
    $router->get('/api/music/group', '\App\Admin\Controllers\MusicGroupController@getMusicGroup');
    $router->get('/api/city', '\App\Admin\Controllers\CityController@getCity');
    $router->get('/api/album', '\App\Admin\Controllers\AlbumController@getAlbum');
    $router->get('/api/tracks', '\App\Admin\Controllers\TrackController@getTracks');
    $router->get('/api/favorite', '\App\Admin\Controllers\FavouriteController@getFavourite');
});
