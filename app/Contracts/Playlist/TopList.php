<?php

namespace App\Contracts\Playlist;



interface TopList
{
    /**
     * @param int $count
     * @return array
     */
    public function getTopTrack(int $count);
    
}