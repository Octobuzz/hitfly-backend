<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'middleware' => 'auth:json'], function () {
    //api-авторизация
    Route::group(['namespace' => 'Api\v1'], function () {
        Route::get('/login/{provider}', 'SocialController@redirectToProvider');
        Route::get('/login/{provider}/callback', 'SocialController@handleProviderCallback');

        Route::post('register', 'AuthController@register');

        Route::post('login', 'AuthController@authenticate');
        Route::get('user', 'AuthController@getAuthenticatedUser');
    });

});

