<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BirthdayCongratulation extends Mailable
{
    use Queueable, SerializesModels;
    public $discountSize;
    public $discountType;
    public $promocode;
    public $user;
    public $video;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $discount, $promocode, $video)
    {
        $this->discountSize = $discount->getDiscountSize();
        $this->discountType = $discount->getDiscountType();
        $this->promocode = $promocode;
        $this->video = $video;
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
