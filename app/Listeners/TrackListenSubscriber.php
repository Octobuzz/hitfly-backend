<?php

namespace App\Listeners;

use App\Events\Track\TrackMinimumListening;

class TrackListenSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(TrackMinimumListening::class, self::class.'@listenedTrack');
    }

    /**
     * @param $trackListen
     */
    public function listenedTrack($trackListen)
    {
        /* @var TrackMinimumListening $trackListen */
        $track = $trackListen->getTrack();
        $track->count_listen = $track->count_listen + 1;
        $track->save();
    }
}
