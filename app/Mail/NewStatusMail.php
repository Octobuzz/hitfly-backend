<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewStatusMail extends Mailable
{
    use Queueable, SerializesModels;
    public $status;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($status, $user)
    {
        $this->status = $status;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.newStatus')
            ->subject(__('emails.newStatus.subject'));
    }
}
