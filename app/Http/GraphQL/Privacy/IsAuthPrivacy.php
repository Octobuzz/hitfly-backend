<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.04.19
 * Time: 11:21.
 */

namespace App\Http\GraphQL\Privacy;

use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Privacy;

class IsAuthPrivacy extends Privacy
{
    public function validate(array $args)
    {
        if (null === Auth::id()) {
            return false;
        } else {
            return true;
        }
    }
}
