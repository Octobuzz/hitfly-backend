<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FewCommentsMonthMail extends Mailable
{
    use Queueable, SerializesModels;
    public $tracks;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param $tracks
     */
    public function __construct(User $user, $tracks)
    {
        $this->tracks = $tracks;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.fewCommentsMonth')
            ->subject(__('emails.fewCommentsMonth.subject'));
    }
}
