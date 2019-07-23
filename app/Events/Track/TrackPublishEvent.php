<?php

namespace App\Events\Track;

use App\Models\Track;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TrackPublishEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $track;

    /**
     * Create a new event instance.
     */
    public function __construct(Track $track)
    {
        $this->track = $track;
    }

    /**
     * @return Track
     */
    public function getTrack(): Track
    {
        return $this->track;
    }
}
