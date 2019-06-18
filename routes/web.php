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
Route::get('/mail-preview', function (\App\BuisnessLogic\Emails\Notification $notification) {
//    $params = [
//        'value' => 'Значение',
//    ];


    return $notification->newCommentNotification(1);
});
Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/test', 'Controller@test');
Route::get('/register-error', 'Auth\RegisterController@registerError');
Route::get('/email-change/{id}/{token}', 'Auth\EmailChangeController@changeEmail');
Route::get('/email-change', 'Auth\EmailChangeController@emailChanged');
Route::get('/email-change-failed', 'Auth\EmailChangeController@emailChangeFailed');
Route::get('/register-success', 'Api\v1\SocialController@registerSuccess')->middleware('web');

Route::group(['namespace' => 'Auth'], function () {
    Route::get('/logout', 'LoginController@logout');
    Route::get('/login/{provider}', 'LoginController@redirectToProvider')->name('social_auth');
    Route::get('/login/{provider}/callback', 'LoginController@handleProviderCallback')->name('social_auth_callback');
});

Route::get('/', 'HomeController@index')->name('home');
Route::get('/{parameter}', 'HomeController@index')->name('home')->where('parameter', '.*');
