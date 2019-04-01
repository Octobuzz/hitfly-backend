<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 15.03.2019
 * Time: 11:19
 */

namespace App\Notifications;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends \Illuminate\Auth\Notifications\ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('emails.resetPassword.subject'))
            ->line(__('emails.resetPassword.entry'))
            ->action(__('emails.resetPassword.resetPassword'), url(config('app.url').route('password.reset', $this->token, false)))
            ->line(trans('emails.resetPassword.linkExpire', ['count' => config('auth.passwords.users.expire')]))
            ->line(__('emails.resetPassword.footer'));
    }
}