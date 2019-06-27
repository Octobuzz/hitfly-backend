<?php

namespace App\Events\Track;

use App\Models\Track;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Музыкальная волна создана
 * Class CreatedMusicWaveEvent.
 */
class CreatedMusicWaveEvent
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

    public function getTrack(): Track
    {
        return $this->track;
    }
}
