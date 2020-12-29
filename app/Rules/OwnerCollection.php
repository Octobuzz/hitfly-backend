<?php

namespace App\Rules;

use App\Models\Collection;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class OwnerCollection implements Rule
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
        $collection = Cache::get('collection_find_'.$value, null);

        if (null === $collection) {
            $collection = Collection::find($value);
            Cache::put('collection_find_'.$value, $collection, 60);
        }

        if (null === $collection) {
            return false;
        }
        $user = Auth::guard('json')->user();

        if ($collection->user_id === $user->id) {
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
        return trans('validation.ownerCollection');
    }
}
