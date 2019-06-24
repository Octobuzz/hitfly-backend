<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LongAgoNotVisited extends Mailable
{
    use Queueable, SerializesModels;
    public $tracks;
    public $user;
    public $importantEvents;
    public $events;
    public $days;

    /**
     * LongAgoNotVisited constructor.
     *
     * @param $days
     * @param $user
     * @param $events
     * @param $importantEvents
     * @param $tracks
     */
    public function __construct($days, User $user, $events, $importantEvents, $tracks)
    {
        $this->tracks = $tracks;
        $this->user = $user;
        $this->events = $events;
        $this->importantEvents = $importantEvents;
        $this->days = $days;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.longAgoNotVisited')
            ->subject(__('emails.longAgoNotVisited.subject'));
    }
}
