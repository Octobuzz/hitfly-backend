<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 05.04.19
 * Time: 11:21.
 */

namespace App\Http\GraphQL\Privacy;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use Rebing\GraphQL\Support\Privacy;

class IsAuthPrivacy extends Privacy
{
    use GraphQLAuthTrait;

    public function validate(array $args)
    {
        return null !== $this->getGuard()->user();
    }
}
