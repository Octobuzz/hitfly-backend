<?php

namespace App\Mail;

use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class FewComments extends Mailable
{
    use Queueable, SerializesModels;
    public $tracks, $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param $tracks
     */
    public function __construct(User $user,$tracks)
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
        return $this->view('emails.notification.fewComments')
            ->subject(__('emails.fewComments.subject'));
    }

}
