<?php

namespace App\Contracts\Playlist;



interface RecommendationList
{
    /**
     * @param int $count
     * @return array
     */
    public function getNewUserPlayList(int $count);

}