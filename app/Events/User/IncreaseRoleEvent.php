<?php

namespace App\Events\User;

use App\Models\Role;
use App\User;

/**
 * Повышение роли пользователя
 * Class IncreaseRoleEvent.
 */
class IncreaseRoleEvent
{
    private $role;
    private $user;

    public function __construct(User $user, $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }
}
