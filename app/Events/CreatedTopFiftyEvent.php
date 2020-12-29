<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreatedTopFiftyEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $idsTrack;

    public function __construct(array $idsTrack)
    {
        $this->idsTrack = $idsTrack;
    }

    /**
     * @return array
     */
    public function getIdsTrack(): array
    {
        return $this->idsTrack;
    }
}
