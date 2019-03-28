<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestForEventMail extends Mailable
{
    use Queueable, SerializesModels;
    public $event,  $user, $eventsList;

    /**
     * RequestForEventMail constructor.
     * @param $event
     * @param User $user
     */
    public function __construct($event, User $user, $eventsList)
    {
        $this->user = $user;
        $this->event = $event;
        $this->eventsList = $eventsList;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.requestForEvent')
            ->subject(__('emails.requestForEvent.subject'));
    }
}
