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

use App\BuisnessLogic\Playlist\Tracks;
use App\BuisnessLogic\SearchIndexing\SearchIndexer;
use App\Models\Track;
use App\User;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Support\Facades\Log;

Route::redirect('/', 'login', 301);
Route::get('/map', 'HomeController@map')->name('map');
Auth::routes(['verify' => true]);

Route::get('/', 'HomeController@index')->name('home');
//Route::get('/test', 'Controller@test');
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
Route::get('/{parameter}', 'HomeController@index')->where('parameter', '.*');
