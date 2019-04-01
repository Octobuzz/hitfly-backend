<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MonthDispatchNotVisitedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $tracks;
    public $user;
    public $recommendation;
    public $events;
    public $month;

    /**
     * MonthDispatchNotVisitedMail constructor.
     *
     * @param User $user
     * @param $events
     * @param $recommendation
     * @param $tracks
     */
    public function __construct(User $user, $events, $recommendation, $tracks)
    {
        $this->tracks = $tracks;
        $this->user = $user;
        $this->events = $events;
        $this->recommendation = $recommendation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.monthDispatchNotVisited')
            ->subject(__('emails.monthDispatchNotVisited.subject'));
    }
}
