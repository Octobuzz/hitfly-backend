<?php

namespace App\BuisnessLogic\Notify;

use App\Interfaces\Notification\BaseUserNotificationInterface;

class BaseNotifyMessage implements BaseUserNotificationInterface
{
    private $messageData;
    private $type;

    public function __construct(string $type, array $messageData)
    {
        $this->messageData = $messageData;
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMessageData(): array
    {
        return [
            'date' => new \DateTime(),
            'type' => $this->getType(),
            'messageData' => $this->messageData,
        ];
    }
}
