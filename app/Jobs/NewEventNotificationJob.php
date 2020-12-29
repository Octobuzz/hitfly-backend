<?php

namespace App\Jobs;

use App\Mail\NewEventNotificationMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NewEventNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    public $events;

    /**
     * RemindForEventJob constructor.
     *
     * @param $events
     * @param User $user
     */
    public function __construct($events, User $user)
    {
        $this->events = $events;
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return \Mail::to($this->user->email)->send(new NewEventNotificationMail($this->events, $this->user));
    }
}
