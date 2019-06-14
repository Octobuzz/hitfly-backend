<?php

namespace App\Interfaces\Notification;

/**
 * Базовый класс уведомлений пользователя
 * Interface BaseUserNotificationInterface.
 */
interface BaseUserNotificationInterface
{
    public function getTitle(): ?string;

    public function getMessage(): string;

    public function getData(): array;
}
