<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class WebSocketChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed                                  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toWebSocket($notifiable);
    }
}
