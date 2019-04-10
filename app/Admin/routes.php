<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->resource('/auth/users', UserController::class);
    $router->resource('/genre', GenreController::class);
    $router->resource('/comment', CommentController::class);
    $router->resource('/collection', CollectionController::class);
    $router->resource('/album', AlbumController::class);
    $router->resource('/music/group', MusicGroupController::class);
    $router->resource('/track', TrackController::class);

    $router->get('/api/users', '\App\Admin\Controllers\UserController@users');
    $router->get('/api/genres', '\App\Admin\Controllers\GenreController@getGenres');
    $router->get('/api/music/group', '\App\Admin\Controllers\MusicGroupController@getMusicGroup');
    $router->get('/api/city', '\App\Admin\Controllers\CityController@getCity');
    $router->get('/api/album', '\App\Admin\Controllers\AlbumController@getAlbum');
});
