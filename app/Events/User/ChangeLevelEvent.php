<?php

namespace App\Events\User;

use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangeLevelEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $level;
    private $user;

    public function __construct(User $user, string  $level)
    {
        $this->level = $level;
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
