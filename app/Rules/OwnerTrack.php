<?php

namespace App\Rules;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Track;
use Illuminate\Contracts\Validation\Rule;

class OwnerTrack implements Rule
{
    use GraphQLAuthTrait;

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

        return $this->getGuard()->user()->id === $track->user_id;
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
