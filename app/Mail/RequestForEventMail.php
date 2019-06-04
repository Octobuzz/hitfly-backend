<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestForEventMail extends Mailable
{
    use Queueable, SerializesModels;
    public $event;
    public $user;
    public $eventsList;

    /**
     * RequestForEventMail constructor.
     *
     * @param $event
     * @param User $user
     */
    public function __construct(User $user, $event, $eventsList)
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
