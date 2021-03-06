<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class IsStar implements Rule
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
        $user = User::find($value);
        if (null === $user) {
            return false;
        }

        return ($user->can('comment.star')) ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.isStar');
    }
}
