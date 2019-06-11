<?php

namespace App\Rules;

use App\Models\Album;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class OwnerAlbum implements Rule
{
    /**
     * Текущий пользователь владелец альбома.
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
        $album = Cache::get('album_find_'.$value, null);

        if (null === $album) {
            $album = Album::find($value);
            Cache::put('album_find_'.$value, $album, 60);
        }

        if (null === $album) {
            return false;
        }
        $user = \Auth::guard('json')->user();

        if ($album->user_id === $user->id) {
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
        return trans('validation.ownerAlbum');
    }
}
