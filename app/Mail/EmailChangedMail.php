<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $user;
    public $url;

    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.emailChanged')
            ->subject(__('emails.emailChanged.subject'));
    }
}
