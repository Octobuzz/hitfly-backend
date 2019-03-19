<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 19.03.2019
 * Time: 14:45
 */

namespace App\BuisnessLogic\Playlist;
use App\Contracts\Playlist\TopList;
use App\Models\Track;

class TopPlayList implements TopList
{

    /**
     * @param int $count
     * @return Track[] array
     */
    public function getTopTrack(int $count) : array
    { //todo получение реальных TOP-ов
        $topList = [];
        $trackList = Track::query()->select('*')->take($count)->get();
        foreach ($trackList as $track) {
            $topList[] = [
                'track_name'=>  $track->track_name,
                'singer'=>  $track->singer,
                'album_img' => $track->album->getAlbumImageURL(),
                'link' => 'url',//todo реальный урл(пока неизвестно куда должна идти ссылка)
                'track_time' => '3:54'//todo получение времени трека
            ];

        }
        return $topList;
    }
}