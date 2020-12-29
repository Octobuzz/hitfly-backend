<?php

namespace App\Notifications;

use App\Channels\WebSocketChannel;
use App\Interfaces\Notification\BaseUserNotificationInterface;
use App\User;
use GatewayWorker\Lib\Gateway;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

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

        try {
            if (
                null !== $userNotification
                && null !== $userNotification->token_web_socket
                && 1 === Gateway::isOnline($userNotification->token_web_socket)
            ) {
                $via[] = WebSocketChannel::class;
            }
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage(), $exception);
        }

        return $via;
    }

    public function toWebSocket(User $user)
    {
        $userNotification = $user->userNotification()->first();
        try {
            Gateway::sendToClient($userNotification->token_web_socket, json_encode([
                'type' => 'notification',
                'id' => $this->id,
                'data' => $this->baseUserNotification->getMessageData(),
            ]));
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage(), $exception->getTrace());
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray(User $user)
    {
        return $this->baseUserNotification->getMessageData();
    }
}
