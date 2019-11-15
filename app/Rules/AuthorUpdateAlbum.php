<?php

namespace App\Rules;

use App\Models\Album;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AuthorUpdateAlbum implements Rule
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
        $album = Album::query()->find($value);

        return Auth::guard('json')->user()->id === $album->user_id;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.authorUpdateAlbum');
    }
}
