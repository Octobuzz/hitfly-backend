<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReachTopMail extends Mailable
{
    use Queueable, SerializesModels;
    public $track;
    public $topUrl;
    public $eventsList;
    public $topCount;

    /**
     * Create a new message instance.
     */
    public function __construct($track, $topUrl, $eventsList, $topCount)
    {
        $this->track = $track;
        $this->topUrl = $topUrl;
        $this->topCount = $topCount;
        $this->eventsList = $eventsList;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.reachTop')
            ->subject(__('emails.reachTop.subject'));
    }
}
