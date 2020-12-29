<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DecreaseStatusMail extends Mailable
{
    use Queueable, SerializesModels;
    public $decreaseStatus;
    public $oldStatus;
    public $user;

    /**
     * понижение статуса.
     */
    public function __construct($decreaseStatus, $oldStatus, $user)
    {
        $this->oldStatus = $oldStatus;
        $this->decreaseStatus = $decreaseStatus;
        $this->user = $user->username;
        $this->to($user->email);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.decreaseStatus')
            ->subject(__('emails.decreaseStatus.subject'));
    }
}
