<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DecreaseLevelMail extends Mailable
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
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.decreaseLevel')
            ->subject(__('emails.decreaseLevel.subject'));
    }
}
