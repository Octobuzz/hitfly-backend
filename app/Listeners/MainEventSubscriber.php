<?php

namespace App\Listeners;

use App\BuisnessLogic\TopFifty;
use App\Events\Track\TrackMinimumListening;
use App\Interfaces\Top\TopWeeklyInterface;
use Illuminate\Support\Facades\Cache;

class MainEventSubscriber
{
    private $topWeekly;

    public function __construct(TopWeeklyInterface $topWeekly)
    {
        $this->topWeekly = $topWeekly;
    }

    public function subscribe($events)
    {
        $events->listen(TrackMinimumListening::class, self::class.'@minimumListening');
        $events->listen(TrackMinimumListening::class, self::class.'@topWeekly');
    }

    public function minimumListening(TrackMinimumListening $trackMinimumListening): void
    {
        $topFifty = Cache::get(TopFifty::TOP_FIFTY_KEY, []);
        $track = $trackMinimumListening->getTrack();

        $listenedTrack = Cache::get(TopFifty::LISTENED_TRACK_KEY, []);
        $listenedTrack[$track->id] = new \DateTime();
        Cache::forever(TopFifty::LISTENED_TRACK_KEY, $listenedTrack);

        $user = $trackMinimumListening->getUser();
        if (null === $user) {
            return;
        }

        $listenedUser = Cache::get(TopFifty::LISTENED_USER_KEY, []);
        $listenedUser[$user->id] = new \DateTime();
        Cache::forever(TopFifty::LISTENED_USER_KEY, $listenedUser);

        $topFifty[$track->id][$user->id] = new \DateTime();
        Cache::forever(TopFifty::TOP_FIFTY_KEY, $topFifty);
    }

    public function topWeekly(TrackMinimumListening $trackMinimumListening): void
    {
        $this->topWeekly->add($trackMinimumListening->getTrack());
    }
}
