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
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Support\Facades\Log;

Route::redirect('/', 'login', 301);
Route::get('/s', function () {

    //алиас
//    $params['body']['actions'] = [
//        'add' => ['index' => 'track','alias' => 'track_new4'],
//        //'dest' => ['index' => 'new_track'],
//    ];
//    $return = null;
//
//    $return = \Elasticsearch::indices()->updateAliases($params);
//    dd($return);
    //новый индекс
//    $params = [
//        'index' => 'track_new2',
//        'body' => [
//
//        ]
//    ];
//
//    $response =  $return = \Elasticsearch::indices()->create($params);
//
//dd($response);



        $aliasName = 'track_new';
        //$aliases = \Elasticsearch::indices()->getAliases();
        $aliases = \Elasticsearch::cat()->indices(array('index' => 'track*'));
    dd($aliases);
        foreach ($aliases as $index => $aliasMapping) {
            if (array_key_exists($aliasName, $aliasMapping['aliases'])) {
                dd($index);
            }
        }

       dd('end');

//    $track = \App\Models\Track::query()->where("id",182)->first();
//    $data = [
//        'body' => [
//            'track_name' => $track->track_name,
//            'singer' => $track->singer,
//            'song_text' => $track->song_text,
//        ],
//        'index' => 'tracks',
//        'type' => \App\Models\Track::class ,
//        'id' => $track->id,
//    ];
//
//    $return = Elasticsearch::index($data);
//    dd($return);
    // $track = \App\Models\Track::query()->where('id', 182)->get();
    //dd($track->only(['track_name', 'singer', 'song_text']));
//    $r = new \App\BuisnessLogic\SearchIndexing\AttributeFiltering();
//    dd($r->trackFilter($track)->toArray());
//    $track = \App\Models\Track::all();
//    $indexer = new \App\BuisnessLogic\SearchIndexing\SearchIndexer();
//    $indexer->index($track, 'track');
//    $params = [
//        'source' => ['index' => 'track'],
//        'dest' => ['index' => 'new_track'],
//    ];
//    $return = null;
//    // Get doc at /my_index/_doc/my_id
//    //try {
//    $return = \Elasticsearch::aliases($params);
//    dd($return);
    // }catch (Missing404Exception $e){
    //     Log::notice($e->getMessage(), $e->getTrace());
    // }
//    //$return = Elasticsearch::get($params);
//    dd($return);
    $params = [
        'index' => ['track'],
        //'type' => 'App\Models\Track',
        'body' => [
            'query' => [
                'match' => [
                    'singer' => 'seodi3',
                    //'title' => 'seodi3',
                ],
            ],
        ],
    ];

    // Get doc at /my_index/_doc/my_id
    $return = Elasticsearch::search($params);
    dd($return);
});
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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/{parameter}', 'HomeController@index')->where('parameter', '.*');
