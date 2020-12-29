<?php

namespace App\Events\Track;

use App\Models\Track;

class EntryTrackInTopFiftyEvent
{
    private $track;
    private $place;

    public function __construct(Track $track, int $place)
    {
        $this->place = $place;
        $this->track = $track;
    }

    /**
     * @return int
     */
    public function getPlace(): int
    {
        return $this->place;
    }

    /**
     * @return Track
     */
    public function getTrack(): Track
    {
        return $this->track;
    }
}
