<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $user;
    public $url;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $email, $url)
    {
        $this->user = $user;
        $this->email = $email;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.emailChange')
            ->subject(__('emails.emailChange.subject'));
    }
}
