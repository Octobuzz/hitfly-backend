<?php

namespace App\Rules;

use App\Models\Comment;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;


class RateNotEstimatedComment implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $comment = Cache::get("comment_find_".$value, null);

        if($comment === null) {
            $comment = Comment::find($value);
            Cache::put("comment_find_" . $value, $comment, 60);
        }



        if (null === $comment) {
            return false;
        }

        if ($comment->estimation === null) {

            return true;
        } else
            return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.rateNotEstimatedComment');
    }
}
