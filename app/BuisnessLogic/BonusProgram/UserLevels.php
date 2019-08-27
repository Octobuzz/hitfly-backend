<?php

namespace App\BuisnessLogic\BonusProgram;

use App\Events\User\ChangeLevelEvent;
use App\Models\Track;
use App\User;

/**
 * Класс повышения уровня пользователя
 * Class UserLevels.
 */
class UserLevels
{
    const MAX_GENRE_COUNT = 5; //максимальное количество жанров, которое понадобится для рассчета

    public function changeUserLevel(User $user)
    {
        if (null === $user->purseBonus) {
            $user->load('purseBonus');
        }
        $balance = $user->purseBonus->balance;
        $keyLevel = array_search($user->level, $user->levels);
        if (false === $keyLevel) {
            throw  new \Exception('Нет такого уровня в иерархии уровней.');
        }
        if ($balance >= config('bonus.levelBonusPoint.'.User::LEVEL_AMATEUR)
            && $balance < config('bonus.levelBonusPoint.'.User::LEVEL_CONNOISSEUR_OF_THE_GENRE)
            && User::LEVEL_AMATEUR !== $user->level && $keyLevel < 1) {
            $user->level = User::LEVEL_AMATEUR;
            $user->save();
            event(new ChangeLevelEvent($user, $user->level));
        } else {
            //получим количество прослушиваний один раз для всех последующих условий
            $countListen = $this->getCountListenedTracksByGenres($user, self::MAX_GENRE_COUNT);
            if ($balance >= config('bonus.levelBonusPoint.'.User::LEVEL_CONNOISSEUR_OF_THE_GENRE)
                && $balance < config('bonus.levelBonusPoint.'.User::LEVEL_SUPER_MUSIC_LOVER)
                && User::LEVEL_CONNOISSEUR_OF_THE_GENRE !== $user->level
                && $keyLevel < 2
                && $this->checkGenresListeners($countListen, 1, config('bonus.levelListenTrack.'.User::LEVEL_CONNOISSEUR_OF_THE_GENRE))
            ) {
                $user->level = User::LEVEL_CONNOISSEUR_OF_THE_GENRE;
                $user->save();
                event(new ChangeLevelEvent($user, $user->level));
            } elseif (
                $balance >= config('bonus.levelBonusPoint.'.User::LEVEL_SUPER_MUSIC_LOVER)
                && User::LEVEL_SUPER_MUSIC_LOVER !== $user->level
                && $keyLevel < 3
                && $this->checkGenresListeners($countListen, self::MAX_GENRE_COUNT, config('bonus.levelListenTrack.'.User::LEVEL_SUPER_MUSIC_LOVER))
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
    public function getCountListenedTracksByGenres(User $user, $countGenres = 1)
    {
        $countArray = $user->listenedTracks()->selectRaw('count(*) as count, genres_bindings.genre_id')->leftJoin('genres_bindings', function ($join) {
            $join->on('genres_bindings.genreable_id', '=', 'listened_tracks.track_id');
            $join->where('genres_bindings.genreable_type', '=', Track::class);
        })->groupBy('genres_bindings.genre_id')
            ->orderBy('count', 'desc')->limit($countGenres)->get()
            ->pluck('count', 'genre_id');
        // $countArray : ключ - id жанра, значение - количество прослушиваний
        return $countArray->toArray();
    }

    /**
     * вычисляет прослушал ли пользователь достаточно треков в жанре.
     *
     * @param $countArray
     * @param int $countGenres кол-во жанров, необходимое для проходение условия
     * @param int $countListen кол-во прослушиваний необходимое для прохождения условия
     *
     * @return bool
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
