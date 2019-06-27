<?php

namespace App\Listeners;

use App\Events\Track\CreateMusicWaveEvent;
use App\Jobs\Track\CreateSignalStrengthJobs;

class ConsoleCommandEventSubsriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(CreateMusicWaveEvent::class, self::class.'@createMusicWaveEvent');
    }

    /**
     * Создание музыкальной волны.
     *
     * @param CreateMusicWaveEvent $event
     */
    public function createMusicWaveEvent(CreateMusicWaveEvent $event)
    {
        CreateSignalStrengthJobs::dispatch($event->getTrack());
    }
}
