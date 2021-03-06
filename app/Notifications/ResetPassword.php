<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 15.03.2019
 * Time: 11:19.
 */

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends \Illuminate\Auth\Notifications\ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->view('emails.password.changePasswordRequest', ['user' => $notifiable, 'url' => url(config('app.url').route('password.reset', $this->token, false))])
            ->subject(__('emails.resetPassword.subject'));
    }
}
