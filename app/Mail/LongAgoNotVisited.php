<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LongAgoNotVisited extends Mailable
{
    use Queueable, SerializesModels;
    public $tracks, $user,$recommendation,$events,$days;

    /**
     * LongAgoNotVisited constructor.
     * @param $days
     * @param $user
     * @param $events
     * @param $recommendation
     * @param $tracks
     */
    public function __construct($days, User $user,$events,$recommendation,$tracks)
    {
        $this->tracks = $tracks;
        $this->user = $user;
        $this->events = $events;
        $this->recommendation = $recommendation;
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
