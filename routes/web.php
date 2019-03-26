<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', 'login', 301);
Route::get('/mail-preview', function(\App\BuisnessLogic\Emails\Notification $notification){
    $params = [
        'value' => 'Значение',
    ];

    return $notification->longAgoNotVisited();
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['namespace' => 'Auth'], function () {
    Route::get('/logout', 'LoginController@logout');
    Route::get('/login/{provider}', 'LoginController@redirectToProvider');
    Route::get('/login/{provider}/callback', 'LoginController@handleProviderCallback');
});