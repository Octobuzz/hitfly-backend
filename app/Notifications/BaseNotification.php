<?php

namespace App\Notifications;

use App\Channels\WebSocketChannel;
use App\Interfaces\Notification\BaseUserNotificationInterface;
use App\User;
use GatewayWorker\Lib\Gateway;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BaseNotification extends Notification
{
    use Queueable;

    private $baseUserNotification;

    public function __construct(BaseUserNotificationInterface $baseUserNotification)
    {
        $this->baseUserNotification = $baseUserNotification;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param User $user
     *
     * @return array
     */
    public function via(User $user)
    {
        $via = ['database'];
        $userNotification = $user->userNotification()->first();

        if (
            null !== $userNotification
            && 1 === Gateway::isOnline($userNotification->token_web_socket)
        ) {
            $via[] = WebSocketChannel::class;
        }

        return $via;
    }

    public function toWebSocket(User $user)
    {
        $userNotification = $user->userNotification()->first();
        Gateway::sendToClient($userNotification->token_web_socket, json_encode([
            'message' => $this->baseUserNotification->getMessage(),
            'title' => $this->baseUserNotification->getTitle(),
            'data' => $this->baseUserNotification->getData(),
        ]));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param User $notifiable
     *
     * @return array
     */
    public function toArray(User $user)
    {
        return [
            'message' => $this->baseUserNotification->getMessage(),
            'title' => $this->baseUserNotification->getTitle(),
            'data' => $this->baseUserNotification->getData(),
        ];
    }
}
