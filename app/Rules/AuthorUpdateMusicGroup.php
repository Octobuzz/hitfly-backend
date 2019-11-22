<?php

namespace App\Rules;

use App\Models\MusicGroup;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AuthorUpdateMusicGroup implements Rule
{
    /**
     * Create a new rule instance.
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
        $musicGroup = MusicGroup::query()->find($value);

        return Auth::guard('json')->user()->id === $musicGroup->creator_group_id;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.authorUpdateMusicGroup');
    }
}
