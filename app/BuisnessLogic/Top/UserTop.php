<?php

namespace App\BuisnessLogic\Top;

use App\Models\Track;

class UserTop
{
    const USER_TOP_CALCULATED = 'USER_TOP_CALCULATED';
    const USER_TOP_TIME = 20; //время кэша

    /**
     * @param int $id
     *
     * @return array
     */
    public static function getTopForUser(int $id): array
    {
        $tracks = Track::query()->where('user_id', $id)
            ->whereNull('music_group_id')
            ->orderByDesc('count_listen')
            ->limit(5)->get();

        $topArray = [];
        if (null !== $tracks) {
            foreach ($tracks as $track) {
                $topArray[] = $track;
            }
        }

        return $topArray;
    }
}
