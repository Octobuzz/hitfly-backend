<?php

namespace App\Rules;

use App\Models\MusicGroup;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class OwnerMusicGroup implements Rule
{
    /**
     * Текущий пользователь владелец музыкальной группы.
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $musicGroup = Cache::get('music_group_find_'.$value, null);

        if (null === $musicGroup) {
            $musicGroup = MusicGroup::find($value);
            Cache::put('music_group_find_'.$value, $musicGroup, 60);
        }

        if (null === $musicGroup) {
            return false;
        }
        $user = Auth::user();

        if ($musicGroup->creator_group_id === $user->id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.ownerMusicGroup');
    }
}
