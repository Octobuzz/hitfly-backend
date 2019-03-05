<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->resource('/genre', GenreController::class);
    $router->resource('/group', GroupController::class);
    $router->resource('/comment', CommentController::class);
    $router->resource('/collection', CollectionController::class);

    $router->get('/api/users', '\App\Admin\Controllers\UserController@users');
    $router->get('/api/genres', '\App\Admin\Controllers\GenreController@getGenres');
});
