<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 01.03.19
 * Time: 18:21.
 */

namespace App\Http\GraphQL\Privacy;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use Rebing\GraphQL\Support\Privacy;

class UserPrivacy extends Privacy
{
    use GraphQLAuthTrait;

    public function validate(array $args)
    {
        if (null === $this->getGuard()->user()) {
            return false;
        }

        if (empty($args['id'])) {
            return false;
        }

        return $args['id'] == $this->getGuard()->user()->id;
    }
}
