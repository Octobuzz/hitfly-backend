<?php

namespace App\BuisnessLogic\Notify;

use App\Interfaces\Notification\BaseUserNotificationInterface;

class BaseNotifyMessage implements BaseUserNotificationInterface
{
    private $message;
    private $title;
    private $data;

    public function __construct(string $message, ?string $title, ?array $data)
    {
        $this->message = $message;
        $this->title = $title;
        $this->data = $data;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getMessage(): string
    {
        return  $this->message;
    }

    public function getData(): array
    {
        return (is_null($this->data)) ? [] : $this->data;
    }
}
