<?php

namespace App\Events\Track;

use App\Models\Track;
use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Событие при прослушаивании минимума трека
 * Class TrackMinimumListening.
 */
class TrackMinimumListening
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $track;
    private $user;

    /**
     * TrackMinimumListening constructor.
     *
     * @param Track      $track
     * @param User| null $user
     */
    public function __construct(Track $track, User $user = null)
    {
        $this->track = $track;
        $this->user = $user;
    }

    /**
     * @return Track
     */
    public function getTrack(): Track
    {
        return $this->track;
    }

    /**
     * @return Track
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
}
