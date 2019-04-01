<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BirthdayCongratulation extends Mailable
{
    use Queueable, SerializesModels;
    public $playLists;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $recommend)
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
