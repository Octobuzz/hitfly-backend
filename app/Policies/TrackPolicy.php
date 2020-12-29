<?php

namespace App\Policies;

use App\Models\Track;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrackPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function view(User $user, Track $track)
    {
    }

    public function create(User $user)
    {
    }

    public function update(User $user, Track $track)
    {
    }

    public function delete(User $user, Track $track): bool
    {
        return $user->id === $track->user_id;
    }
}
