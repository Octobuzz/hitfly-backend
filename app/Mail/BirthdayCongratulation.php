<?php

namespace App\Mail;

use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class BirthdayCongratulation extends Mailable
{
    use Queueable, SerializesModels;
    public $playLists, $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,$recommend)
    {
        $this->playLists = $recommend;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.birthday')
            ->subject(__('emails.birthday.subject'));
    }

}
