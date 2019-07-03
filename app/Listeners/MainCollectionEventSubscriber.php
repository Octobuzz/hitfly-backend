<?php

namespace App\Listeners;

use App\BuisnessLogic\TopFifty;
use App\Events\Track\TrackMinimumListening;
use Illuminate\Support\Facades\Cache;

class MainCollectionEventSubscriber
{
    public function subscribe($events)
    {
        $events->listen(TrackMinimumListening::class, self::class.'@minimumListening');
    }

    public function minimumListening(TrackMinimumListening $trackMinimumListening)
    {
        $topFifty = Cache::get(TopFifty::TOP_FIFTY_KEY, []);
        $track = $trackMinimumListening->getTrack();

        $lestenedTrack = Cache::get(TopFifty::LISTENED_TRACK_KEY, []);
        $lestenedTrack[$track->id] = new \DateTime();
        Cache::forever(TopFifty::LISTENED_TRACK_KEY, $lestenedTrack);

        $user = $trackMinimumListening->getUser();
        if (null === $user) {
            return;
        }

        $lestenedUser = Cache::get(TopFifty::LISTENED_USER_KEY, []);
        $lestenedUser[$user->id] = new \DateTime();
        Cache::forever(TopFifty::LISTENED_USER_KEY, $lestenedUser);

        $topFifty[$track->id][$user->id] = new \DateTime();
        Cache::forever(TopFifty::TOP_FIFTY_KEY, $topFifty);
    }
}
