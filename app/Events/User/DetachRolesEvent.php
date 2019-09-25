<?php


namespace App\Events\User;


use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DetachRolesEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $ids;
    private $user;

    public function __construct(User $user, array $level)
    {
        $this->ids = $level;
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    public function getIds(): array
    {
        return $this->ids;
    }
}
