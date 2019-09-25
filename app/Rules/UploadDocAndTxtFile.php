<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UploadDocAndTxtFile implements Rule
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
        switch ($value->getClientMimeType()) {
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                return true;
                break;
            case 'application/vnd.oasis.opendocument.text':
                return true;
                break;
            case 'text/plain':
                return true;
                break;
            case 'application/msword':
                return true;
                break;
            default:
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
        return trans('validation.invalidFileFormat');
    }
}
