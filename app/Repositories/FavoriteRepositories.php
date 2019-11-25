<?php

namespace App\Repositories;

use App\Models\Favourite;
use App\User;

class FavoriteRepositories
{
    /**
     * @param Favourite $favourite
     * @param int       $modelId
     * @param User      $user
     *
     * @return int
     */
    public function countFavorite(
        Favourite $favourite,
        int $modelId,
        User $user
    ): int {
        $favoriteType = 'favouriteable_type';
        $favoriteId = 'favouriteable_id';

        return
            Favourite::query()
                ->where($favoriteType, '=', get_class($favourite->favouriteable()->getRelated()))
                ->where($favoriteId, $modelId)
                ->where('user_id', '<>', $user->id)
                ->count();
    }
}
