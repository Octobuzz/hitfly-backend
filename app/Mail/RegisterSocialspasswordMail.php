<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterSocialspasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $pass;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $pass)
    {
        $this->user = $user;
        $this->pass = $pass;
        $this->to($user->email);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.password.socialPassword')
            ->subject(__('emails.socialPassword.subject'));
    }
}
