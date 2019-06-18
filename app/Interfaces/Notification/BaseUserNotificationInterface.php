<?php

namespace App\Interfaces\Notification;

/**
 * Базовый класс уведомлений пользователя
 * Interface BaseUserNotificationInterface.
 */
interface BaseUserNotificationInterface
{
    public function getType(): ?string;

    public function getMessageData(): array;
}
