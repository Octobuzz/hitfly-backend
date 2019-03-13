<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 01.03.19
 * Time: 18:21.
 */

namespace App\Http\GraphQL\Privacy;

use App\Models\Comment;
use App\Policies\CommentPolicy;
use Rebing\GraphQL\Support\Privacy;

class RatePrivacy extends Privacy
{
    public function validate(array $args)
    {
        //dd($args);
        return false;
        //Comment::query()->first();

        if (null === Auth::id()) {
            return false;
        }

        return $args['id'] == Auth::id();
    }
}
