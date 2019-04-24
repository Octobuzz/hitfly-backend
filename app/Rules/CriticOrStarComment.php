<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CriticOrStarComment implements Rule
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
        return (\Auth::user()->can('comment.сricic')||\Auth::user()->can('comment.star'))?true:false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.criticComment');
    }
}
