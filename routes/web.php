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
Route::get('/s', function () {
//    $q = User::query();
//    $q->whereHas('roles', function ($query) {
//        $query->whereIn('slug', ['critic']);
//    })->chunk(1, function ($collectionModel) {
//        dump($collectionModel);
//    });
//    die();

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
    //$indexer = new SearchIndexer();
//    $params = [
//        'index' => 'track_read',
//        'type' => 'App\Models\Track',
//        'id' => 188,
//    ];
//    $aliases = \Elasticsearch::exists($params);
//    dd($aliases);
//    \Elasticsearch::delete($params);
//    $s = $indexer->checkAndCreateAlias('track', SearchIndexer::POSTFIX_WRITE);
//    $s = $indexer->checkAndCreateAlias('track', SearchIndexer::POSTFIX_READ);

    $aliasName = 'track_new';
    //$aliases = \Elasticsearch::indices()->existsAlias(['name' => 'track_1566991682_write','index' => 'track_1566991682']);
    //$aliases = \Elasticsearch::indices()->getAliases();
    $aliases = \Elasticsearch::cat()->indices(['index' => 'track*']);
    dd($aliases);
//    foreach ($aliases as $index => $aliasMapping) {
//        if (array_key_exists($aliasName, $aliasMapping['aliases'])) {
//            dd($index);
//        }
//    }
//
//    dd('end');

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
        'index' => ['user_read'],
        //'type' => 'App\Models\Track',
        'body' => [
            'query' => [
                'match' => [
                    //'singer' => 'seodi3',
                    //'title' => 'seodi3',
                    'username' => 'Kaleb Bergnaum',
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
