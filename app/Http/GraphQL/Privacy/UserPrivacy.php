<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 01.03.19
 * Time: 18:21.
 */

namespace App\Http\GraphQL\Privacy;

use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Privacy;

class UserPrivacy extends Privacy
{
    public function validate(array $args)
    {
        if (null === Auth::id()) {
            return false;
        }

        return $args['id'] == Auth::id();
    }
}