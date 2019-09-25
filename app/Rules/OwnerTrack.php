<?php

namespace App\Rules;

use App\Models\Track;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class OwnerTrack implements Rule
{
    /**
     * является ли пользователь владельцем трека.
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
        $track = Track::query()->find($value);
        if (null === $track) {
            return false;
        }

        return Auth::user()->id === $track->user_id;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.ownerTrack');
    }
}
