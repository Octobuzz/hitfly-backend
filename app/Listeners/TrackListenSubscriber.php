<?php

namespace App\Listeners;

use App\Events\Track\TrackMinimumListening;
use App\Models\ListenedTrack;
use Illuminate\Support\Facades\Auth;

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

        //сохранение статистики прослушиваний
        $this->userListeningTrack($track);
    }

    private function userListeningTrack($track)
    {
        $user = Auth::user();
        $listening = ListenedTrack::firstOrCreate(
            ['user_id' => $user->id, 'track_id' => $track->id]
        );
    }
}
