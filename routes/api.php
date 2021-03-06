<?php

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

Route::group(['prefix' => 'v1'], function () {
    //api-авторизация
    Route::group(['namespace' => 'Api\v1'], function () {
        Route::get('/login/{provider}', 'SocialController@redirectToProvider')->name('link_socials');
        Route::get('/login/{provider}/callback', 'SocialController@handleProviderCallback');
        Route::get('/register/providers', 'SocialController@getProvidersList');

        Route::post('register', 'AuthController@register');

        Route::post('login', 'AuthController@authenticate');
        Route::get('user', 'AuthController@getAuthenticatedUser');
    });
});
