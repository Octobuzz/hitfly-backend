<?php

namespace App\Jobs;

use App\Mail\LongAgoNotVisited;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LongAgoNotVisitedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tracks;
    public $user;
    public $importantEvents;
    public $events;
    public $days;

    /**
     * FewCommentsJob constructor.
     *
     * @param $user
     * @param $events
     * @param $importantEvents
     * @param $tracks
     */
    public function __construct($days, User $user, $events, $importantEvents, $tracks)
    {
        $this->days = $days;
        $this->user = $user;
        $this->tracks = $tracks;
        $this->events = $events;
        $this->importantEvents = $importantEvents;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return \Mail::to($this->user->email)->send(new LongAgoNotVisited($this->days, $this->user, $this->events, $this->importantEvents, $this->tracks));
    }
}
