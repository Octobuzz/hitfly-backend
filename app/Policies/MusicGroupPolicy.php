<?php

namespace App\Policies;

use App\User;
use App\Models\MusicGroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class MusicGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the music group.
     *
     * @param \App\User              $user
     * @param \App\Models\MusicGroup $musicGroup
     *
     * @return mixed
     */
    public function view(User $user, MusicGroup $musicGroup)
    {
    }

    /**
     * Determine whether the user can create music groups.
     *
     * @param \App\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
    }

    /**
     * Determine whether the user can update the music group.
     *
     * @param \App\User              $user
     * @param \App\Models\MusicGroup $musicGroup
     *
     * @return mixed
     */
    public function update(User $user, MusicGroup $musicGroup)
    {
    }

    /**
     * Determine whether the user can delete the music group.
     *
     * @param \App\User              $user
     * @param \App\Models\MusicGroup $musicGroup
     *
     * @return mixed
     */
    public function delete(User $user, MusicGroup $musicGroup)
    {
        return $user->id == $musicGroup->creator_group_id;
    }

    /**
     * Determine whether the user can restore the music group.
     *
     * @param \App\User              $user
     * @param \App\Models\MusicGroup $musicGroup
     *
     * @return mixed
     */
    public function restore(User $user, MusicGroup $musicGroup)
    {
    }

    /**
     * Determine whether the user can permanently delete the music group.
     *
     * @param \App\User              $user
     * @param \App\Models\MusicGroup $musicGroup
     *
     * @return mixed
     */
    public function forceDelete(User $user, MusicGroup $musicGroup)
    {
    }
}
