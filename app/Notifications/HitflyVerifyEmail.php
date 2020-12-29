<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Mail;

class HitflyVerifyEmail extends VerifyEmailBase
{
    public $redirectTo;

    public function __construct($redirectTo)
    {
        $this->redirectTo = $redirectTo;
    }

    /**
     * @param mixed $notifiable
     *
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }
//        return Mail::to($notifiable->email)->send(new \App\Mail\VerifyEmail($this->verificationUrl($notifiable)));
        return (new MailMessage())
            ->view('emails.notification.verifyEmail', ['link' => $this->verificationUrl($notifiable)])
            ->subject(__('emails.verifyEmail.subject'));
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify', Carbon::now()->addMinutes(1440), ['id' => $notifiable->getKey(), 'redirect' => $this->redirectTo]
        );
    }

    public function via($notifiable)
    {
        return ['mail'];
    }
}
