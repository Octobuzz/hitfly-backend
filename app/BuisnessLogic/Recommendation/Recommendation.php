<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 19.03.2019
 * Time: 18:56.
 */

namespace App\BuisnessLogic\Recommendation;

use App\Contracts\Playlist\RecommendationList;
use App\Helpers\DateHelpers;
use App\Helpers\PictureHelpers;
use App\Models\Collection;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Date\Date;

class Recommendation implements RecommendationList
{
    public function getNewUserPlayList(int $count)
    {
        // TODO: Implement getRecommendPlaylist() method. получить реальные рекомендации
        $collect = null;
        $collection = Collection::query()
            ->where('is_admin', '=', 1)
            ->orderBy('created_at', 'DESC')->limit($count)->get();
        foreach ($collection as $item){
            /** @var Collection $item */
            $collect[] = [
                'name' => $item->title,
                'date' => Date::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('j F'),
                'count_tracks' => $item->tracks->count().' '.DateHelpers::getNumEnding($item->tracks->count(), ['трек', 'трека', 'треков']),
                'list_img' => PictureHelpers::resizePicture($item, 200, 200),//config('app.url').'/images/emails/img/new-year-playlist.png',
                'link' => config('app.url').'/playlist/'.$item->id,
            ];
        }
        return $collect;
//        return [
//            [
//                'name' => 'Название плейлиста',
//                'date' => '7 декабря',
//                'count_tracks' => '256 '.DateHelpers::getNumEnding(256, ['трек', 'трека', 'треков']),
//                'list_img' => config('app.url').'/images/emails/img/new-year-playlist.png',
//                'link' => '/url',
//            ],
//            [
//                'name' => 'Плейлист 2',
//                'date' => '7 декабря',
//                'count_tracks' => '101 '.DateHelpers::getNumEnding(101, ['трек', 'трека', 'треков']),
//                'list_img' => config('app.url').'/images/emails/img/new-year-playlist.png',
//                'link' => '/url',
//            ],
//        ];
    }

    public function getBirthdayPlayLists(User $user)
    {
        // TODO: Implement getBirthdayPlayLists() method. рекомендации в письме на день рождения
        return null;
//        return [
//            [
//                'name' => 'Название плейлиста',
//                'date' => '7 декабря',
//                'count_tracks' => '256 '.DateHelpers::getNumEnding(256, ['трек', 'трека', 'треков']),
//                'list_img' => '/url',
//                'link' => '/url',
//            ],
//            [
//                'name' => 'Плейлист 2',
//                'date' => '7 декабря',
//                'count_tracks' => '100 '.DateHelpers::getNumEnding(100, ['трек', 'трека', 'треков']),
//                'list_img' => '/url',
//                'link' => '/url',
//            ],
//        ];
    }
}
