<?php

namespace App\Events\User;

class CreatedTopFiveTrack
{
    private $tracks;

    /**
     * CreatedUserTopFifty constructor.
     *
     * @param int[] $tracks
     */
    public function __construct($tracks)
    {
        $this->tracks = $tracks;
    }

    /**
     * @return int[]
     */
    public function getTracksId(): array
    {
        return $this->tracks;
    }
}
