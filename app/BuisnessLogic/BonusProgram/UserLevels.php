<?php

namespace App\BuisnessLogic\BonusProgram;

use App\Events\User\ChangeLevelEvent;
use App\Models\ListenedTrack;
use App\Models\Track;
use App\User;

class UserLevels
{
    public function changeUserLevel(User $user)
    {
        $balance = $user->purseBonus->balance;
        $keyLevel = array_search($user->level, $user->levels);
        if (false === $keyLevel) {
            throw  new \Exception('Нет такого уровня в иерархии уровней.');
        }
        if ($balance >= 400 && $balance < 3000 && User::LEVEL_AMATEUR !== $user->level && $keyLevel < 1) {
            $user->level = User::LEVEL_AMATEUR;
            $user->save();
            event(new ChangeLevelEvent($user, $user->level));
        } else {
            //получим количество прослушиваний один раз для всех последующих условий
            $countListen = $this->getCountListenedTracksOfGenres($user, 1);
            if ($balance >= 3000 && $balance < 5000
                && User::LEVEL_CONNOISSEUR_OF_THE_GENRE !== $user->level
                && $keyLevel < 2
                && $this->checkGenresListeners($countListen, 1, 2500)
            ) {
                $user->level = User::LEVEL_CONNOISSEUR_OF_THE_GENRE;
                $user->save();
                event(new ChangeLevelEvent($user, $user->level));
            } elseif (
                $balance >= 5000
                && User::LEVEL_SUPER_MUSIC_LOVER !== $user->level
                && $keyLevel < 3
                && $this->checkGenresListeners($countListen, 1, 10000)
            ) {
                $user->level = User::LEVEL_SUPER_MUSIC_LOVER;
                $user->save();
                event(new ChangeLevelEvent($user, $user->level));
            }
        }
    }

    /**
     * получить количество прослушанных треков в разрезе жанров.
     *
     * @param User $user
     * @param int  $countGenres
     *
     * @return array
     */
    private function getCountListenedTracksOfGenres(User $user, $countGenres = 1)
    {
        $query = ListenedTrack::query()->selectRaw('count(*) as count, genres_bindings.genre_id')
            ->where('listened_tracks.user_id', $user->id)
            ->leftJoin('genres_bindings', function ($join) {
                $join->on('genres_bindings.genreable_id', '=', 'listened_tracks.track_id');
                $join->where('genres_bindings.genreable_type', '=', Track::class);
            });
        $countArray = $query->groupBy('genres_bindings.genre_id')->get()->pluck('count', 'genre_id')->toArray();
        arsort($countArray);
        $countArray = array_slice($countArray, 0, $countGenres, true);
        // $countArray : ключ - id жанра, значение - количество прослушиваний
        return $countArray;
    }

    /**
     * вычисляет прослушал ли пользователь достаточно треков в жанре.
     *
     * @param $countArray
     * @param int $countGenres кол-во жанров, необходимое для проходение условия
     * @param int $countListen кол-во прослушиваний необходимое для прохождения условия
     */
    private function checkGenresListeners(array $countArray, $countGenres = 1, $countListen = 100)
    {
        $condition = 0;
        foreach ($countArray as $item) {
            if ($item >= $countListen) {
                ++$condition;
            }
            if ($countGenres === $condition) {
                return true;
            }
        }

        return false;
    }
}
