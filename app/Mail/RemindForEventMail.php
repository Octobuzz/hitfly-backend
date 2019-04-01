<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemindForEventMail extends Mailable
{
    use Queueable, SerializesModels;
    public $event;
    public $user;
    public $eventsList;

    /**
     * RemindForEventMail constructor.
     *
     * @param $event
     * @param User $user
     * @param $eventsList
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
        return $this->view('emails.notification.remindForEvent')
            ->subject(__('emails.remindForEvent.subject'));
    }
}
