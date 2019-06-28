<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Выполнение задания
 * Class CompletedTaskEvent.
 */
class CompletedTaskEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $user;
    private $point; // количество полученных балов
    private $name; // название задания

    /**
     * CompletedTaskEvent constructor.
     *
     * @param User   $user
     * @param string $name
     * @param int    $point
     */
    public function __construct(User $user, string $name, int $point)
    {
        $this->user = $user;
        $this->point = $point;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getPoint(): int
    {
        return $this->point;
    }
}
