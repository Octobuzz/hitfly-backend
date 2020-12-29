<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewEventNotificationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $events;
    public $user;

    /**
     * RemindForEventMail constructor.
     *
     * @param $events
     * @param User $user
     */
    public function __construct($events, User $user)
    {
        $this->user = $user;
        $this->events = $events;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.newEventNotification')
            ->subject(__('emails.newEventNotification.subject'));
    }
}
