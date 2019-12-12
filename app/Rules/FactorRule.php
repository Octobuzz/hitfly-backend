<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FactorRule implements Rule
{
    /**
     * валидация множителей.
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
        $factorArray = [
            1, 1.5, 2, 2.5, 3, 3.5,
        ];
        if (in_array($value, $factorArray)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.factor');
    }
}
