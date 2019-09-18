<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 19.03.2019
 * Time: 14:45.
 */

namespace App\BuisnessLogic\Playlist;

use App\Contracts\Playlist\TracksContract;
use App\Models\Track;
use App\BuisnessLogic\TopFifty;
use Illuminate\Support\Facades\Cache;

class Tracks implements TracksContract
{
    /**
     * @param int $count
     *
     * @return Track[] array
     */
    public function getTopTrack(int $count): array
    {
        $idsTracks = Cache::get(TopFifty::TOP_FIFTY_KEY_CALCULATED, null);
        if (null === $idsTracks || $count < 1) {
            return [];
        }
        $topList = [];
        $idsTracksChanks = array_chunk($idsTracks, $count, true);
        $trackList = Track::query()->select('*')->whereIn('id', $idsTracksChanks[0])->get();
        foreach ($trackList as $track) {
            $position = array_search($track->id, $idsTracks);
            if (false !== $position) {
                $topList[$position] = [
                    'position' => $position + 1, //реальное место в плейлисте
                    'track_name' => $track->track_name,
                    'singer' => $track->singer,
                    'album_img' => env('APP_URL').$track->getImageUrl(),
                    'link' => env('APP_URL').'/top50', //todo реальный урл(пока неизвестно куда должна идти ссылка)
                    'track_time' => '3:54', //todo получение времени трека
                    'user' => $track->user,
                ];
            }
        }

        return $topList;
    }

    public function getNewTracks(int $count)
    {
        //TODO проверить в итоге под какие условия попадают новые треки
        $trackList = [];
        $tracks = Track::query()->select('*')->orderByDesc('created_at')->take($count)->get();
        foreach ($tracks as $track) {
            $trackList[] = [
                'track_name' => $track->track_name,
                'singer' => $track->singer,
                'album_img' => env('APP_URL').$track->getImageUrl(),
                'link' => 'url', //todo реальный урл(пока неизвестно куда должна идти ссылка)
//                'track_time' => '3:54', //todo получение времени трека
            ];
        }

        return $trackList;
    }
}
