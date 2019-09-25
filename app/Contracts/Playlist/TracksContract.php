<?php

namespace App\Contracts\Playlist;

interface TracksContract
{
    /**
     * @param int $count
     *
     * @return array
     */
    public function getTopTrack(int $count);

    public function getNewTracks(int $count);
}
