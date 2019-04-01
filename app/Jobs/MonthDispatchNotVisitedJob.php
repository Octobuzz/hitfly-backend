<?php

namespace App\Jobs;

use App\Mail\MonthDispatchNotVisitedMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MonthDispatchNotVisitedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tracks,$user,$recommendation,$events;

    /**
     * MonthDispatchNotVisitedJob constructor.
     * @param User $user
     * @param $events
     * @param $recommendation
     * @param $tracks
     */
    public function __construct(User $user,$events,$recommendation,$tracks)
    {
        $this->user = $user;
        $this->tracks = $tracks;
        $this->events = $events;
        $this->recommendation = $recommendation;

    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return \Mail::to($this->user->email)->send(new MonthDispatchNotVisitedMail($this->user,$this->events,$this->recommendation,$this->tracks));
    }
}
