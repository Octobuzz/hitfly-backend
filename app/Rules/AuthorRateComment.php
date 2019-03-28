<?php

namespace App\Rules;

use App\Models\Comment;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class AuthorRateComment implements Rule
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
        $comment = Cache::get('comment_find_'.$value, null);

        if (null === $comment) {
            $comment = Comment::find($value);
            Cache::put('comment_find_'.$value, $comment, 60);
        }

        if (null === $comment) {
            return false;
        }
        $user = \Auth::guard('json')->user();

        if ($comment->commentable->user_id === $user->id) {
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
        return trans('validation.authorRateComment');
    }
}
