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
Auth::routes(['verify' => true]);

Route::get('/', 'HomeController@index')->name('home');
Route::get('/register-error', 'Auth\RegisterController@registerError');
Route::get('/register-genres', 'Auth\RegisterController@showGenreForm')->name('register.genres');
Route::post('/register-genres', 'Auth\RegisterController@setGenres');
Route::get('/email-change/{id}/{token}', 'Auth\EmailChangeController@changeEmail');
Route::get('/email-change', 'Auth\EmailChangeController@emailChanged');
Route::get('/email-change-failed', 'Auth\EmailChangeController@emailChangeFailed');
Route::get('/register-success', 'Api\v1\SocialController@registerSuccess')->middleware('web');

Route::group(['namespace' => 'Auth'], function () {
    Route::get('/logout', 'LoginController@logout');
    Route::get('/login/{provider}', 'LoginController@redirectToProvider')->name('social_auth');
    Route::get('/login/{provider}/callback', 'LoginController@handleProviderCallback')->name('social_auth_callback');
});

Route::get('/policy', 'HomeController@policy')->name('policy');

Route::get('/', 'HomeController@index')->name('home');
Route::get('/recommended', 'HomeController@recommended');
Route::get('/super-melomaniac', 'HomeController@superMelomaniac');
Route::get('/top50', 'HomeController@topFifty');
Route::get('/listening_now', 'HomeController@listeningNow');
Route::get('/weekly_top', 'HomeController@weeklyTop');
Route::get('/new_songs', 'HomeController@newSongs');
Route::get('/genre/{genre}', 'HomeController@genre');
Route::get('/playlist/{playlist}', 'HomeController@playlist');
Route::get('/news/{news}', 'HomeController@news');
Route::get('/bonus-program', 'HomeController@bonusProgram');
Route::get('/faq', 'HomeController@faq');
Route::get('/user/{user}/music', 'HomeController@userMusic');
Route::get('/user/{user}/music/tracks', 'HomeController@userMusicTracks');
Route::get('/user/{user}/music/albums', 'HomeController@user');
Route::get('/user/{user}/music/playlists', 'HomeController@userMusicPlaylists');
Route::get('/user/{user}/reviews', 'HomeController@user');
Route::get('/user/{user}/user-reviews', 'HomeController@userUserReviews');
Route::get('/upload', 'HomeController@upload');
Route::get('/about', 'HomeController@user');
Route::get('/{parameter}', 'HomeController@index')->where('parameter', '.*');
