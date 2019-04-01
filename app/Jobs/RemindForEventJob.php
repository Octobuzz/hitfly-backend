<?php

namespace App\Jobs;

use App\Mail\RemindForEventMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RemindForEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    public $eventsList;
    public $event;

    /**
     * RemindForEventJob constructor.
     *
     * @param $event
     * @param User $user
     * @param $eventsList
     */
    public function __construct($event, User $user, $eventsList)
    {
        $this->event = $event;
        $this->user = $user;
        $this->eventsList = $eventsList;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return \Mail::to($this->user->email)->send(new RemindForEventMail($this->event, $this->user, $this->eventsList));
    }
}
