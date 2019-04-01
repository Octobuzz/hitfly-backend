<?php

namespace App\Jobs;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\RequestForEventMail;

class RequestForEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    public $event;
    public $eventsList;

    /**
     * RequestForEventMail constructor.
     *
     * @param User $user
     * @param $event
     */
    public function __construct(User $user, $event, $eventsList)
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
        return \Mail::to($this->user->email)->send(new RequestForEventMail($this->event, $this->user, $this->eventsList));
    }
}
