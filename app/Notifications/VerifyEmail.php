<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class VerifyEmail extends VerifyEmailBase
{
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return (new MailMessage())
            ->subject(__('emails.verifyEmailAddress'))
            ->line(__('emails.clickButton'))
            ->action(
                __('emails.verifyEmailAddress'),
                $this->verificationUrl($notifiable)
            )
            ->line(__('emails.ignoreEmail'));
    }
}
