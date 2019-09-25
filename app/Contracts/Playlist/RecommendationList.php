<?php

namespace App\Contracts\Playlist;

use App\User;

interface RecommendationList
{
    /**
     * @param int $count
     *
     * @return array
     */
    public function getNewUserPlayList(int $count);

    public function getBirthdayPlayLists(User $user);
}
